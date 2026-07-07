<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Document;
use App\Models\Evaluation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportFromCareerOps extends Command
{
    protected $signature = 'career-ops:import';
    protected $description = 'Import evaluations and applications from career-ops project';

    public function handle()
    {
        $this->info('Starting import from career-ops...');

        $careerOpsPath = config('app.career_ops_path', '../career-ops');
        $applicationsFile = "{$careerOpsPath}/data/applications.md";

        if (!File::exists($applicationsFile)) {
            $this->error("File not found: {$applicationsFile}");
            return 1;
        }

        $content = File::get($applicationsFile);
        $evaluations = $this->parseApplicationsMd($content);

        $imported = 0;
        $updated = 0;

        foreach ($evaluations as $eval) {
            $company = trim($eval['company'] ?? '');
            $position = trim($eval['role'] ?? '');

            if (!$company || !$position) {
                continue;
            }

            // Find or create evaluation
            $evaluation = Evaluation::where('company', $company)
                ->where('position', $position)
                ->first();

            if (!$evaluation) {
                $evaluation = Evaluation::create([
                    'rank' => $eval['id'],
                    'evaluation_date' => $eval['date'] ?? now()->toDateString(),
                    'company' => $company,
                    'position' => $position,
                    'score' => $eval['score'] ?? null,
                    'status' => $eval['status'] ?? 'evaluated',
                ]);
                $imported++;
                $this->line("✓ Created evaluation: {$company} → {$position}");
            } else {
                $evaluation->update([
                    'score' => $eval['score'] ?? $evaluation->score,
                    'status' => $eval['status'] ?? $evaluation->status,
                ]);
                $updated++;
                $this->line("◆ Updated evaluation: {$company}");
            }

            // Import cover letter if exists
            if (!empty($eval['cover'])) {
                $this->importDocument($evaluation, 'cover', $eval['cover'], $careerOpsPath);
            }

            // Import summary if exists
            if (!empty($eval['summary'])) {
                $this->importDocument($evaluation, 'summary', $eval['summary'], $careerOpsPath);
            }

            // Create application if it doesn't exist
            Application::firstOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                ],
                [
                    'company' => $company,
                    'position' => $position,
                    'application_date' => $eval['date'] ?? now()->toDateString(),
                    'status' => 'En Revisión',
                    'score' => $eval['score'] ?? null,
                    'link' => $eval['jd_url'] ?? null,
                    'notes' => $eval['notes'] ?? null,
                ]
            );
        }

        $this->info("\n✅ Import complete!");
        $this->info("   Imported: {$imported} evaluations");
        $this->info("   Updated: {$updated} evaluations");

        return 0;
    }

    private function parseApplicationsMd(string $content): array
    {
        $lines = explode("\n", $content);
        $rows = [];
        $inTable = false;

        foreach ($lines as $line) {
            if (str_contains($line, '---')) {
                continue;
            }
            if (!str_starts_with(trim($line), '|')) {
                $inTable = false;
                continue;
            }
            if (str_contains($line, '| # |')) {
                $inTable = true;
                continue;
            }
            if (!$inTable) {
                continue;
            }

            $cells = array_map('trim', explode('|', trim($line, '|')));
            if (count($cells) < 6 || $cells[0] === '#') {
                continue;
            }

            $rows[] = [
                'id' => $cells[0],
                'date' => $cells[1],
                'company' => $cells[2],
                'role' => $cells[3],
                'score' => $cells[4],
                'status' => strtolower($cells[5]),
                'cover' => $cells[6] ?? '',
                'summary' => $cells[7] ?? '',
                'pdf' => $cells[8] ?? '',
                'notes' => $cells[10] ?? '',
            ];
        }

        return $rows;
    }

    private function importDocument(Evaluation $evaluation, string $type, string $mdLink, string $basePath): void
    {
        // Extract path from markdown link [emoji](path)
        preg_match('/\[(.*?)\]\((.*?)\)/', $mdLink, $matches);
        $path = $matches[2] ?? null;

        if (!$path) {
            return;
        }

        // Resolve full path (convert ../output/xxx to /var/www_parent/output/xxx)
        $path = str_replace('../', '', $path);
        $fullPath = "{$basePath}/{$path}";

        if (!File::exists($fullPath)) {
            $this->warn("   ⚠ File not found: {$fullPath}");
            return;
        }

        $content = File::get($fullPath);

        // Check if document already exists
        $doc = Document::where('evaluation_id', $evaluation->id)
            ->where('type', $type)
            ->first();

        if ($doc) {
            $doc->update(['content' => $content]);
        } else {
            Document::create([
                'evaluation_id' => $evaluation->id,
                'type' => $type,
                'content' => $content,
                'external_path' => $path,
            ]);
        }
    }
}
