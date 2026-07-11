<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;

class RecordApplication extends Command
{
    protected $signature = 'application:record
        {company : Company name}
        {position : Role title}
        {--fit= : Fit score 0-100}
        {--status=Postulado : Application status}
        {--link= : Job posting URL}
        {--cv= : CV markdown name in cv/ (no extension)}
        {--cover= : Cover markdown name in cover_letters/ (no extension)}
        {--evaluation= : Fit evaluation summary}';

    protected $description = 'Record a /apply result into the applications table so it shows in the admin';

    public function handle(): int
    {
        $app = Application::updateOrCreate(
            ['company' => $this->argument('company'), 'position' => $this->argument('position')],
            [
                'application_date' => now()->toDateString(),
                'fit_score' => $this->option('fit') ? (int) $this->option('fit') : null,
                'status' => $this->option('status'),
                'link' => $this->option('link'),
                'cv_path' => $this->option('cv'),
                'cover_path' => $this->option('cover'),
                'evaluation_notes' => $this->option('evaluation'),
            ]
        );

        $this->info("Recorded application #{$app->id}: {$app->company} — {$app->position} (fit {$app->fit_score}/100)");
        $this->line('View in admin: /admin/job-applications');

        return self::SUCCESS;
    }
}
