<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScanJobs extends Command
{
    protected $signature = 'jobs:scan {--fresh : Delete existing non-favorite listings before scanning}';

    protected $description = 'Scan public job APIs and store listings matching the profile';

    // Keywords that define the target profile
    protected array $keywords = [
        'laravel', 'php', 'full stack', 'fullstack', 'full-stack', 'node',
        'javascript', 'angular', 'backend', 'python', 'developer', 'engineer',
    ];

    protected string $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $deleted = JobListing::where('is_favorite', false)->where('is_applied', false)->delete();
            $this->warn("Deleted {$deleted} old listings.");
        }

        $found = 0;
        $found += $this->scanRemotive();
        $found += $this->scanRemoteOK();
        $found += $this->scanWeWorkRemotely();
        $found += $this->scanJobicy();
        $found += $this->scanHimalayas();

        $this->newLine();
        $this->info("Scan complete. {$found} matching listings saved/updated.");
        $this->line('Total in database: ' . JobListing::count());

        // Keep the Claude Code /rank + /apply workflow in sync
        $this->call('jobs:export');

        return self::SUCCESS;
    }

    protected function matches(string $text): bool
    {
        $t = strtolower($text);
        foreach ($this->keywords as $k) {
            if (str_contains($t, $k)) {
                return true;
            }
        }
        return false;
    }

    protected function save(array $data): bool
    {
        try {
            JobListing::updateOrCreate(
                ['source' => $data['source'], 'url' => $data['url']],
                $data
            );
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function get(string $url)
    {
        return Http::withHeaders(['User-Agent' => $this->ua, 'Accept' => '*/*'])
            ->timeout(30)->get($url);
    }

    protected function scanRemotive(): int
    {
        $this->line('Scanning Remotive...');
        $count = 0;
        try {
            $res = $this->get('https://remotive.com/api/remote-jobs?search=developer&limit=100');
            foreach ($res->json('jobs', []) as $j) {
                if (!$this->matches($j['title'] ?? '')) continue;
                if ($this->save([
                    'source' => 'Remotive',
                    'external_id' => (string)($j['id'] ?? ''),
                    'title' => $j['title'] ?? '',
                    'company' => $j['company_name'] ?? '',
                    'url' => $j['url'] ?? '',
                    'salary' => $j['salary'] ?: null,
                    'job_type' => $j['job_type'] ?? null,
                    'location' => $j['candidate_required_location'] ?? null,
                    'tags' => implode(', ', $j['tags'] ?? []),
                    'published_at' => isset($j['publication_date']) ? substr($j['publication_date'], 0, 10) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  Remotive failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }

    protected function scanRemoteOK(): int
    {
        $this->line('Scanning RemoteOK...');
        $count = 0;
        try {
            $res = $this->get('https://remoteok.com/api');
            $jobs = $res->json() ?? [];
            foreach (array_slice($jobs, 1) as $j) {
                $title = $j['position'] ?? $j['title'] ?? '';
                // For RemoteOK match only on title to avoid tag noise
                if (!$this->matches($title)) continue;
                $salary = null;
                if (!empty($j['salary_min'])) {
                    $salary = '$' . number_format($j['salary_min']) . ' - $' . number_format($j['salary_max'] ?? 0);
                }
                if ($this->save([
                    'source' => 'RemoteOK',
                    'external_id' => (string)($j['id'] ?? ''),
                    'title' => $title,
                    'company' => $j['company'] ?? '',
                    'url' => $j['url'] ?? '',
                    'salary' => $salary,
                    'location' => $j['location'] ?? null,
                    'tags' => implode(', ', $j['tags'] ?? []),
                    'published_at' => isset($j['date']) ? substr($j['date'], 0, 10) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  RemoteOK failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }

    protected function scanWeWorkRemotely(): int
    {
        $this->line('Scanning WeWorkRemotely...');
        $count = 0;
        try {
            $res = $this->get('https://weworkremotely.com/categories/remote-programming-jobs.rss');
            $xml = $res->body();
            preg_match_all('/<item>(.*?)<\/item>/s', $xml, $items);
            foreach ($items[1] as $it) {
                preg_match('/<title>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/title>/s', $it, $mt);
                preg_match('/<link>(.*?)<\/link>/s', $it, $ml);
                preg_match('/<pubDate>(.*?)<\/pubDate>/s', $it, $md);
                $title = trim($mt[1] ?? '');
                if (!$this->matches($title)) continue;
                // Split "Company: Title" format
                $company = '';
                if (str_contains($title, ':')) {
                    [$company, $title] = array_map('trim', explode(':', $title, 2));
                }
                if ($this->save([
                    'source' => 'WeWorkRemotely',
                    'title' => $title,
                    'company' => $company,
                    'url' => trim($ml[1] ?? ''),
                    'published_at' => isset($md[1]) ? date('Y-m-d', strtotime($md[1])) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  WeWorkRemotely failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }

    protected function scanJobicy(): int
    {
        $this->line('Scanning Jobicy...');
        $count = 0;
        try {
            $res = $this->get('https://jobicy.com/api/v2/remote-jobs?count=50&tag=developer');
            foreach ($res->json('jobs', []) as $j) {
                $title = $j['jobTitle'] ?? '';
                if (!$this->matches($title)) continue;
                if ($this->save([
                    'source' => 'Jobicy',
                    'external_id' => (string)($j['id'] ?? ''),
                    'title' => $title,
                    'company' => $j['companyName'] ?? '',
                    'url' => $j['url'] ?? '',
                    'salary' => (!empty($j['annualSalaryMin'])) ? '$' . $j['annualSalaryMin'] . ' - $' . ($j['annualSalaryMax'] ?? '') : null,
                    'job_type' => is_array($j['jobType'] ?? null) ? implode(', ', $j['jobType']) : ($j['jobType'] ?? null),
                    'location' => $j['jobGeo'] ?? null,
                    'published_at' => isset($j['pubDate']) ? substr($j['pubDate'], 0, 10) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  Jobicy failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }

    protected function scanHimalayas(): int
    {
        $this->line('Scanning Himalayas...');
        $count = 0;
        try {
            $res = $this->get('https://himalayas.app/jobs/api?limit=100');
            foreach ($res->json('jobs', []) as $j) {
                $title = $j['title'] ?? '';
                if (!$this->matches($title)) continue;
                $url = $j['applicationLink'] ?? ($j['guid'] ?? '');
                if (!$url) continue;
                if ($this->save([
                    'source' => 'Himalayas',
                    'title' => $title,
                    'company' => $j['companyName'] ?? '',
                    'url' => $url,
                    'location' => is_array($j['locationRestrictions'] ?? null) ? implode(', ', $j['locationRestrictions']) : null,
                    'tags' => is_array($j['categories'] ?? null) ? implode(', ', $j['categories']) : null,
                    'published_at' => isset($j['pubDate']) ? date('Y-m-d', (int)$j['pubDate']) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  Himalayas failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }
}
