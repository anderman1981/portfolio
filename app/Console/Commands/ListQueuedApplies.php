<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use Illuminate\Console\Command;

class ListQueuedApplies extends Command
{
    protected $signature = 'jobs:queued';

    protected $description = 'List job listings queued for /apply (Option B: process these in the terminal with Claude Code)';

    public function handle(): int
    {
        $queued = JobListing::queued()->where('is_applied', false)->get();

        if ($queued->isEmpty()) {
            $this->info('No jobs queued for /apply. Queue some from /admin/job-listings.');
            return self::SUCCESS;
        }

        $this->info("{$queued->count()} job(s) queued for /apply:");
        $this->newLine();
        foreach ($queued as $job) {
            $this->line("• <fg=cyan>{$job->title}</> @ {$job->company}");
            $this->line("  {$job->url}");
        }
        $this->newLine();
        $this->line('Run <fg=yellow>/apply <url></> for each in Claude Code, then mark applied in the admin.');

        return self::SUCCESS;
    }
}
