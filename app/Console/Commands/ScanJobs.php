<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use App\Services\JobScorer;
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

    protected int $duplicates = 0;

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
        $found += $this->scanGetOnBoard();

        $this->newLine();
        $this->info("Scan complete. {$found} matching listings saved/updated, {$this->duplicates} cross-source duplicates skipped.");
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

    /**
     * Normalize, score and upsert one listing.
     * `seniority` and `timezones` are scoring hints only, not columns.
     * A new listing whose company+title already exists from another source is skipped.
     */
    protected function save(array $data): bool
    {
        try {
            $scorer = app(JobScorer::class);
            $data['description'] = JobScorer::plainText($data['description'] ?? null);
            $result = $scorer->score($data);
            unset($data['seniority'], $data['timezones']);

            $data['score'] = $result['score'];
            $data['score_breakdown'] = $result['breakdown'];
            $data['eligible_region'] = $result['eligible_region'];
            $data['dedupe_hash'] = JobScorer::dedupeHash($data['company'] ?? null, $data['title'] ?? null);

            $existing = JobListing::where('source', $data['source'])->where('url', $data['url'])->first();
            if (! $existing && $data['dedupe_hash']) {
                $twin = JobListing::where('dedupe_hash', $data['dedupe_hash'])->whereNull('duplicate_of')->exists();
                if ($twin) {
                    $this->duplicates++;
                    return false;
                }
            }

            JobListing::updateOrCreate(
                ['source' => $data['source'], 'url' => $data['url']],
                $data
            );
            return true;
        } catch (\Throwable $e) {
            $this->warn('  save failed: ' . $e->getMessage());
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
                    'description' => $j['description'] ?? null,
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
                    'description' => $j['description'] ?? null,
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
                preg_match('/<region>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/region>/s', $it, $mr);
                preg_match('/<description>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/description>/s', $it, $mdesc);
                $title = trim($mt[1] ?? '');
                if (!$this->matches($title)) continue;
                [$company, $title] = $this->splitWwrTitle($title);
                if ($this->save([
                    'source' => 'WeWorkRemotely',
                    'title' => $title,
                    'company' => $company,
                    'url' => trim($ml[1] ?? ''),
                    'location' => isset($mr[1]) ? trim($mr[1]) : null,
                    'description' => isset($mdesc[1]) ? html_entity_decode($mdesc[1]) : null,
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
                    'description' => $j['jobDescription'] ?? ($j['jobExcerpt'] ?? null),
                    'seniority' => $j['jobLevel'] ?? null,
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
            // The browse feed is capped at 20 jobs across every category; the search
            // endpoint returns developer roles, 20 per page. Dedupe by URL across queries.
            $jobs = [];
            foreach (['laravel', 'php', 'full stack', 'backend', 'node'] as $q) {
                foreach ([1, 2] as $page) {
                    $res = $this->get('https://himalayas.app/jobs/api/search?q=' . urlencode($q) . '&page=' . $page);
                    foreach ($res->json('jobs', []) as $j) {
                        $jobs[$j['applicationLink'] ?? ($j['guid'] ?? '')] = $j;
                    }
                }
            }
            foreach ($jobs as $j) {
                $title = $j['title'] ?? '';
                if (!$this->matches($title)) continue;
                $url = $j['applicationLink'] ?? ($j['guid'] ?? '');
                if (!$url) continue;
                if ($this->save([
                    'source' => 'Himalayas',
                    'title' => $title,
                    'company' => $j['companyName'] ?? $this->companyFromHimalayasUrl($url),
                    'url' => $url,
                    'location' => $this->himalayasLocation($j['locationRestrictions'] ?? null),
                    'salary' => !empty($j['maxSalary']) ? trim(($j['currency'] ?? '') . ' ' . ($j['minSalary'] ?? '') . ' - ' . $j['maxSalary']) : null,
                    'tags' => is_array($j['categories'] ?? null) ? implode(', ', $j['categories']) : null,
                    'description' => $j['description'] ?? ($j['excerpt'] ?? null),
                    'seniority' => is_array($j['seniority'] ?? null) ? implode(', ', $j['seniority']) : null,
                    'timezones' => is_array($j['timezoneRestrictions'] ?? null) ? $j['timezoneRestrictions'] : null,
                    'published_at' => isset($j['pubDate']) ? date('Y-m-d', (int)$j['pubDate']) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  Himalayas failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }

    /** Himalayas returns either plain country names or {name: ...} objects. */
    protected function himalayasLocation($restrictions): ?string
    {
        if (!is_array($restrictions) || $restrictions === []) {
            return null;
        }
        $names = array_map(fn ($r) => is_array($r) ? ($r['name'] ?? '') : (string) $r, $restrictions);
        return implode(', ', array_filter($names)) ?: null;
    }

    /**
     * WWR titles look like "Company: Title". Split on the first ": " (colon + space)
     * so URLs such as "https://shiperp.com/: PHP Web Developer" don't yield company "https".
     */
    public function splitWwrTitle(string $raw): array
    {
        $raw = trim($raw);
        if (! preg_match('/^(.+?):\s+(.+)$/s', $raw, $m)) {
            return ['', $raw];
        }
        $company = trim($m[1]);
        // Company given as a URL → keep the bare domain
        if (preg_match('#^(?:https?:)?//#i', $company) || preg_match('#^[\w.-]+\.[a-z]{2,}/?$#i', $company)) {
            $company = preg_replace('#^(?:https?:)?//(?:www\.)?#i', '', rtrim($company, '/'));
        }

        return [$company, trim($m[2])];
    }

    /** himalayas.app/companies/{slug}/jobs/... → "Slug Words" */
    protected function companyFromHimalayasUrl(string $url): string
    {
        return preg_match('#/companies/([^/]+)/#', $url, $m) ? ucwords(str_replace('-', ' ', $m[1])) : '';
    }

    /**
     * Get on Board (getonbrd.com) — LATAM-focused board with a public JSON:API.
     * Salaries are monthly USD; seniority ids: 1 no experience, 2 junior, 3 semi senior, 4 senior, 5 expert.
     */
    protected function scanGetOnBoard(): int
    {
        $this->line('Scanning Get on Board...');
        $count = 0;
        $seniority = [1 => 'Junior', 2 => 'Junior', 3 => 'Mid-level', 4 => 'Senior', 5 => 'Senior'];
        try {
            $jobs = [];
            foreach (['laravel', 'php', 'full stack', 'fullstack', 'backend', 'node'] as $q) {
                $res = $this->get('https://www.getonbrd.com/api/v0/search/jobs?per_page=100&expand=%5B%22company%22%5D&query=' . urlencode($q));
                foreach ($res->json('data', []) as $j) {
                    $jobs[$j['id']] = $j;
                }
            }
            // Programming category, newest first, filtered by the profile keywords
            $res = $this->get('https://www.getonbrd.com/api/v0/categories/programming/jobs?per_page=100&expand=%5B%22company%22%5D');
            foreach ($res->json('data', []) as $j) {
                if ($this->matches($j['attributes']['title'] ?? '')) {
                    $jobs[$j['id']] = $j;
                }
            }

            foreach ($jobs as $id => $j) {
                $a = $j['attributes'] ?? [];
                $title = $a['title'] ?? '';
                if (!$this->matches($title)) continue;

                $countries = implode(', ', $a['countries'] ?? []);
                // fully_remote = anywhere; remote_local = remote but must reside in the company's country;
                // hybrid / no_remote = on-site in the listed countries.
                $location = match ($a['remote_modality'] ?? null) {
                    'fully_remote' => 'Anywhere (fully remote)',
                    'remote_local' => 'Remote, local residency required' . ($countries && $countries !== 'Remote' ? " ({$countries})" : ''),
                    default => trim($countries . ' (' . str_replace('_', '-', $a['remote_modality'] ?? 'on-site') . ')'),
                };

                $salary = null;
                if (!empty($a['max_salary'])) {
                    $salary = 'USD ' . ($a['min_salary'] ?? '') . ' - ' . $a['max_salary'] . ' / month';
                }

                $description = implode("\n", array_filter([
                    $a['description'] ?? null, $a['functions'] ?? null, $a['desirable'] ?? null, $a['projects'] ?? null,
                ]));

                if ($this->save([
                    'source' => 'GetOnBoard',
                    'external_id' => (string) $id,
                    'title' => $title,
                    'company' => $a['company']['data']['attributes']['name'] ?? '',
                    'url' => $j['links']['public_url'] ?? "https://www.getonbrd.com/jobs/{$id}",
                    'salary' => $salary,
                    'job_type' => $a['remote_modality'] ?? null,
                    'location' => $location,
                    'description' => $description ?: null,
                    'seniority' => $seniority[$a['seniority']['data']['id'] ?? 0] ?? null,
                    'published_at' => isset($a['published_at']) ? date('Y-m-d', (int) $a['published_at']) : null,
                ])) $count++;
            }
        } catch (\Throwable $e) {
            $this->error('  Get on Board failed: ' . $e->getMessage());
        }
        $this->line("  → {$count} jobs");
        return $count;
    }
}
