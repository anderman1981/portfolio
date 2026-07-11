<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use Illuminate\Console\Command;

class ExportJobsForRank extends Command
{
    protected $signature = 'jobs:export {--path= : Output path for seen_jobs.json}';

    protected $description = 'Export job_listings to job_scraper/seen_jobs.json for the /rank and /apply Claude Code workflow';

    // High-signal keywords for the candidate profile
    protected array $high = ['laravel', 'php', 'full stack', 'fullstack', 'full-stack'];
    protected array $medium = ['node', 'python', 'angular', 'javascript', 'backend', 'developer', 'engineer'];

    public function handle(): int
    {
        $path = $this->option('path') ?: base_path('job_scraper/seen_jobs.json');
        @mkdir(dirname($path), 0755, true);

        // Preserve existing statuses (applied/ranked/skipped) across exports
        $existing = [];
        if (file_exists($path)) {
            $existing = json_decode(file_get_contents($path), true)['seen'] ?? [];
        }

        $seen = [];
        $listings = JobListing::active()->get();

        foreach ($listings as $job) {
            $key = $job->url;
            $prev = $existing[$key] ?? null;

            $seen[$key] = [
                'title' => $job->title,
                'company' => $job->company ?: '',
                'url' => $job->url,
                'salary' => $job->salary ?: '',
                'location' => $job->location ?: '',
                'source' => $job->source,
                'first_seen' => $prev['first_seen'] ?? optional($job->published_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
                'fit' => $prev['fit'] ?? $this->fit($job->title),
                // Keep prior workflow status; mark applied ones from the DB flag
                'status' => $job->is_applied ? 'evaluated' : ($prev['status'] ?? 'new'),
            ];
        }

        file_put_contents($path, json_encode(['seen' => $seen], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Exported ' . count($seen) . ' listings to ' . $path);
        $counts = collect($seen)->groupBy('fit')->map->count();
        $this->line("Fit breakdown: high={$counts->get('high',0)} medium={$counts->get('medium',0)} low={$counts->get('low',0)}");

        return self::SUCCESS;
    }

    protected function fit(string $title): string
    {
        $t = strtolower($title);
        foreach ($this->high as $k) {
            if (str_contains($t, $k)) return 'high';
        }
        foreach ($this->medium as $k) {
            if (str_contains($t, $k)) return 'medium';
        }
        return 'low';
    }
}
