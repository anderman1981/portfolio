<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bridges CV chatbot messages to the AMR ecosystem (n8n → Slack #cv).
 * The chatbot only submits the message + metadata; n8n does the routing.
 */
class ChatBridgeService
{
    /** Keywords that classify a message as `cv` intent (ES + EN). */
    protected array $cvKeywords = [
        'cv', 'hoja de vida', 'resume', 'curriculum', 'currículum',
        'vacante', 'empleo', 'trabajo', 'job', 'position', 'puesto',
        'entrevista', 'interview', 'contratar', 'hiring', 'hire', 'reclut',
        'perfil', 'profile', 'candidat', 'salario', 'salary', 'disponib',
    ];

    /**
     * Forward a visitor message to the bridge. Fire-and-forget; never breaks the chat.
     *
     * @return bool  whether it was accepted by the bridge
     */
    public function forward(array $data): bool
    {
        $payload = [
            'source' => 'home_chatbot',
            'channel' => config('services.cv_bridge.channel', 'cv'),
            'intent' => $this->classify($data['message'] ?? ''),
            'conversation_id' => $data['conversation_id'] ?? null,
            'user_name' => $data['user_name'] ?? null,
            'user_email' => $data['user_email'] ?? null,
            'message' => $data['message'] ?? '',
            'page_url' => $data['page_url'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ];

        // Preferred path: n8n (does classification/routing).
        if ($webhook = config('services.cv_bridge.webhook')) {
            if ($this->postJson($webhook, $payload)) {
                return true;
            }
            // n8n unreachable/inactive → fall through to direct Slack if available.
        }

        // Fallback / direct path: post straight to Slack.
        if ($slack = config('services.cv_bridge.slack_webhook')) {
            return $this->postJson($slack, ['text' => $this->slackText($payload)]);
        }

        return false; // nothing configured yet
    }

    /**
     * Human handoff: alert Anderson on Slack + WhatsApp with a direct link
     * to the conversation in the admin so he can jump straight in.
     */
    public function notifyHandoff(array $data): void
    {
        $convId = $data['conversation_id'] ?? '';
        $name = $data['user_name'] ?? 'Un visitante';
        $email = $data['user_email'] ?? '—';
        $message = $data['message'] ?? '';

        $url = rtrim((string) config('app.url'), '/').'/amrTechAdmin/chat-inbox?session='.urlencode($convId);

        $text = "🙋 *{$name} quiere hablar contigo* (bot desactivado)\n"
            ."*Email:* {$email}\n"
            ."*Último mensaje:* {$message}\n"
            ."👉 Ir a la conversación: {$url}";

        // Slack
        if ($slack = config('services.cv_bridge.slack_webhook')) {
            $this->postJson($slack, ['text' => $text]);
        }

        // WhatsApp via OpenClaw (pluggable webhook). Contract: {to, message}.
        if ($wa = config('services.cv_bridge.whatsapp_webhook')) {
            $this->postJson($wa, [
                'to' => config('services.cv_bridge.admin_whatsapp'),
                'message' => "🙋 {$name} quiere hablar contigo en el chat del CV.\nEmail: {$email}\nMensaje: {$message}\nAbrir: {$url}",
                'url' => $url,
            ]);
        }
    }

    protected function postJson(string $url, array $body): bool
    {
        try {
            $req = Http::timeout(6)->acceptJson();
            if ($token = config('services.cv_bridge.token')) {
                $req = $req->withHeaders(['X-Bridge-Token' => $token]);
            }

            return $req->post($url, $body)->successful();
        } catch (\Throwable $e) {
            Log::warning('CV bridge forward failed: '.$e->getMessage());

            return false;
        }
    }

    /** Slack message body when posting directly (no n8n). */
    protected function slackText(array $p): string
    {
        $intent = strtoupper($p['intent']);
        $emoji = $intent === 'CV' ? ':briefcase:' : ':grey_question:';
        $lines = array_filter([
            "{$emoji} *Nuevo mensaje del chatbot* — intent: *{$intent}*",
            $p['user_name'] ? "*Nombre:* {$p['user_name']}" : null,
            $p['user_email'] ? "*Email:* {$p['user_email']}" : null,
            "*Mensaje:* {$p['message']}",
            $p['page_url'] ? "*Página:* {$p['page_url']}" : null,
            "`conversation_id: ".($p['conversation_id'] ?? 'n/a')."`",
        ]);

        return implode("\n", $lines);
    }

    /**
     * Classify intent. CV-related keywords => `cv`; otherwise `review`
     * (still routed to Slack for manual review, per the routing rules).
     */
    public function classify(string $message): string
    {
        $t = mb_strtolower($message);
        foreach ($this->cvKeywords as $k) {
            if (str_contains($t, $k)) {
                return 'cv';
            }
        }

        return 'review';
    }
}
