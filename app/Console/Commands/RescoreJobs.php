<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use App\Services\JobScorer;
use Illuminate\Console\Command;

class RescoreJobs extends Command
{
    protected $signature = 'jobs:rescore {--no-export : Skip refreshing seen_jobs.json}';

    protected $description = 'Recompute pre-score, region eligibility and cross-source duplicates for all job listings (run after tuning config/job_search.php)';

    public function handle(JobScorer $scorer): int
    {
        $total = 0;

        JobListing::query()->orderBy('id')->chunkById(200, function ($listings) use ($scorer, &$total) {
            foreach ($listings as $job) {
                $result = $scorer->score([
                    'title' => $job->title,
                    'tags' => $job->tags,
                    'description' => $job->description,
                    'location' => $job->location,
                    'salary' => $job->salary,
                    'seniority' => $job->score_breakdown['seniority_level'] ?? null,
                ]);

                $job->forceFill([
                    'score' => $result['score'],
                    'score_breakdown' => $result['breakdown'],
                    'eligible_region' => $result['eligible_region'],
                    'dedupe_hash' => JobScorer::dedupeHash($job->company, $job->title),
                ])->saveQuietly();
                $total++;
            }
        });

        $dupes = $this->markDuplicates();

        $this->info("Rescored {$total} listings. {$dupes} marked as cross-source duplicates.");
        $this->table(['Band', 'Count'], collect(['high', 'medium', 'low'])->map(fn ($b) => [
            $b,
            JobListing::active()->get(['score'])->filter(fn ($j) => $scorer->band($j->score) === $b)->count(),
        ])->all());
        $this->line('Region: eligible=' . JobListing::active()->where('eligible_region', true)->count()
            . ' restricted=' . JobListing::active()->where('eligible_region', false)->count()
            . ' unknown=' . JobListing::active()->whereNull('eligible_region')->count());

        if (! $this->option('no-export')) {
            $this->call('jobs:export');
        }

        return self::SUCCESS;
    }

    /**
     * Within each dedupe group keep one canonical row (applied > favorite > richest description > oldest)
     * and point the rest at it.
     */
    protected function markDuplicates(): int
    {
        JobListing::query()->update(['duplicate_of' => null]);

        $marked = 0;
        $hashes = JobListing::whereNotNull('dedupe_hash')
            ->groupBy('dedupe_hash')->havingRaw('count(*) > 1')->pluck('dedupe_hash');

        foreach ($hashes as $hash) {
            $group = JobListing::where('dedupe_hash', $hash)->get()->sortBy([
                fn ($a, $b) => $b->is_applied <=> $a->is_applied,
                fn ($a, $b) => $b->is_favorite <=> $a->is_favorite,
                fn ($a, $b) => mb_strlen($b->description ?? '') <=> mb_strlen($a->description ?? ''),
                fn ($a, $b) => $a->id <=> $b->id,
            ])->values();

            $keep = $group->shift();
            foreach ($group as $dupe) {
                $dupe->forceFill(['duplicate_of' => $keep->id])->saveQuietly();
                $marked++;
            }
        }

        return $marked;
    }
}
