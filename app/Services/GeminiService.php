<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Gemini client with transparent multi-key failover.
 *
 * Configure several keys (primary first). If one hits its quota (429) or fails,
 * the service marks it as cooling down and moves to the next key automatically.
 * It always retries from the primary once its cooldown expires — transparent to
 * the user, so conversations never break just because one account ran out.
 */
class GeminiService
{
    protected string $model = 'gemini-flash-latest';

    protected int $cooldownMinutes = 5; // exhausted key rests briefly (Gemini limits are often per-minute)

    /** All configured keys, primary first, empties removed. */
    public function keys(): array
    {
        return collect(config('services.gemini.keys', []))
            ->map(fn ($k) => trim((string) $k))
            // Accept both AI Studio key formats: classic "AIza..." and newer "AQ....".
            ->filter(fn ($k) => str_starts_with($k, 'AIza') || str_starts_with($k, 'AQ.'))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Generate content, failing over across keys.
     *
     * @return array{ok: bool, text: ?string, error: ?string}
     */
    public function generate(string $prompt, array $generationConfig = []): array
    {
        $keys = $this->keys();
        if (empty($keys)) {
            return ['ok' => false, 'text' => null, 'error' => 'No hay claves de Gemini configuradas.'];
        }

        $lastError = null;

        foreach ($keys as $index => $key) {
            if ($this->isCoolingDown($index)) {
                continue; // this key is resting; skip it
            }

            try {
                // Disable "thinking" so output tokens go to the actual reply
                // (2.5/flash-latest otherwise spend the budget thinking → empty text).
                $genConfig = $generationConfig ?: ['temperature' => 0.9, 'maxOutputTokens' => 500];
                $genConfig['thinkingConfig'] = ['thinkingBudget' => 0];

                $res = Http::timeout(30)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$key}",
                    [
                        'contents' => [['parts' => [['text' => $prompt]]]],
                        'generationConfig' => $genConfig,
                    ]
                );

                if ($res->successful()) {
                    $text = data_get($res->json(), 'candidates.0.content.parts.0.text');
                    if ($text) {
                        return ['ok' => true, 'text' => $text, 'error' => null];
                    }
                    $lastError = 'Respuesta vacía de Gemini.';

                    continue;
                }

                // Quota/limit (429/403) or unusable key (404) → rest it and try the next.
                if (in_array($res->status(), [429, 403, 404])) {
                    $this->markCoolingDown($index);
                    Log::info("Gemini key #{$index} no disponible (HTTP {$res->status()}), pasando a la siguiente.");
                    $lastError = "Key #{$index} no disponible (HTTP {$res->status()}).";

                    continue;
                }

                $lastError = "Gemini HTTP {$res->status()}.";
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning("Gemini key #{$index} error: {$e->getMessage()}");
            }
        }

        return ['ok' => false, 'text' => null, 'error' => $lastError ?: 'Todas las claves de Gemini están sin cupo por ahora.'];
    }

    protected function cacheKey(int $index): string
    {
        return "gemini_key_cooldown_{$index}";
    }

    protected function isCoolingDown(int $index): bool
    {
        return Cache::has($this->cacheKey($index));
    }

    protected function markCoolingDown(int $index): void
    {
        Cache::put($this->cacheKey($index), true, now()->addMinutes($this->cooldownMinutes));
    }
}
