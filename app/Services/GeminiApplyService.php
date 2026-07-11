<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GeminiApplyService
{
    protected string $model = 'gemini-2.5-flash';

    /**
     * Run the full "apply" workflow for a job listing using Gemini:
     * evaluate fit, draft a tailored CV and cover letter, persist everything,
     * and return the created Application.
     */
    public function apply(JobListing $job): Application
    {
        $key = config('services.gemini.key');
        if (!$key || Str::startsWith($key, 'your')) {
            throw new RuntimeException('GEMINI_API_KEY no configurada. Pega una key válida en .env.');
        }

        $profile = $this->readSkill('01-candidate-profile.md');
        $framework = $this->readSkill('04-job-evaluation.md');
        $style = $this->readSkill('03-writing-style.md');

        $prompt = $this->buildPrompt($job, $profile, $framework, $style);

        $response = Http::timeout(120)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$key}", [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gemini API error: ' . $response->status() . ' ' . $response->body());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
        $data = json_decode($text, true);
        if (!is_array($data) || !isset($data['cv_markdown'], $data['cover_markdown'])) {
            throw new RuntimeException('Respuesta de Gemini con formato inesperado.');
        }

        // Persist CV + cover markdown to disk (rendered to PDF by /download-doc)
        $slug = Str::slug($job->company ?: 'company') . '_' . Str::random(4);
        $cvName = "main_{$slug}";
        $coverName = "cover_{$slug}";

        @mkdir(base_path('cv'), 0755, true);
        @mkdir(base_path('cover_letters'), 0755, true);
        file_put_contents(base_path("cv/{$cvName}.md"), $data['cv_markdown']);
        file_put_contents(base_path("cover_letters/{$coverName}.md"), $data['cover_markdown']);

        // Record the application so it shows in the admin
        return Application::updateOrCreate(
            ['company' => $job->company ?: 'Unknown', 'position' => $job->title],
            [
                'application_date' => now()->toDateString(),
                'fit_score' => (int) ($data['fit_score'] ?? 0),
                'status' => 'Postulado',
                'link' => $job->url,
                'cv_path' => $cvName,
                'cover_path' => $coverName,
                'evaluation_notes' => $data['evaluation_summary'] ?? null,
            ]
        );
    }

    protected function readSkill(string $file): string
    {
        $path = base_path(".claude/skills/job-application-assistant/{$file}");
        return file_exists($path) ? file_get_contents($path) : '';
    }

    protected function buildPrompt(JobListing $job, string $profile, string $framework, string $style): string
    {
        return <<<PROMPT
You are a job application assistant. Using the candidate profile, evaluation framework, and writing style guide below, evaluate the job and draft a tailored CV and cover letter.

## JOB POSTING
Title: {$job->title}
Company: {$job->company}
Location: {$job->location}
Salary: {$job->salary}
URL: {$job->url}
Tags: {$job->tags}

## CANDIDATE PROFILE
{$profile}

## EVALUATION FRAMEWORK
{$framework}

## WRITING STYLE GUIDE
{$style}

## RULES
- Never fabricate skills or experience. If a requirement is a genuine gap, acknowledge it honestly and frame adjacent experience instead.
- CV in English. Cover letter matches the posting language (default English).
- Follow the writing style guide strictly (no em-dashes, no cliches, first person active voice).
- Keep the CV to roughly 2 pages of content and the cover letter to one page.

## OUTPUT
Return ONLY a JSON object with these exact keys:
{
  "fit_score": <integer 0-100>,
  "evaluation_summary": "<3-4 sentence fit evaluation: technical, experience, gaps>",
  "cv_markdown": "<full tailored CV in Markdown>",
  "cover_markdown": "<full tailored cover letter in Markdown>"
}
PROMPT;
    }
}
