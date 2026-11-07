<?php

namespace App\Services;

/**
 * Deterministic 0-100 pre-score for a job listing against the candidate profile.
 *
 * This is a cheap triage signal computed at ingest time so /rank only spends
 * agent effort on promising postings. It never replaces /apply's evaluation.
 * Weights live in config/job_search.php.
 */
class JobScorer
{
    protected array $cfg;

    public function __construct(?array $config = null)
    {
        $this->cfg = $config ?? config('job_search');
    }

    /**
     * @param  array{title?:string, tags?:?string, description?:?string, location?:?string, salary?:?string, seniority?:?string, timezones?:?array}  $job
     * @return array{score:int, eligible_region:?bool, breakdown:array}
     */
    public function score(array $job): array
    {
        $title = mb_strtolower($job['title'] ?? '');
        $haystack = mb_strtolower(implode(' ', [$title, $job['tags'] ?? '', $job['description'] ?? '']));

        [$stack, $matched] = $this->stackPoints($haystack);
        $role = $this->hasAny($title, $this->cfg['role_terms']) ? $this->cfg['role_points'] : 0;
        [$seniorityLevel, $seniority] = $this->seniority($title, mb_strtolower($job['seniority'] ?? ''));
        $eligible = $this->eligibleRegion($job['location'] ?? null, $job['description'] ?? null, $job['timezones'] ?? null);
        $region = $this->cfg['region']['points'][match ($eligible) {
            true => 'eligible',
            false => 'restricted',
            null => 'unknown',
        }];
        [$salaryLevel, $salary] = $this->salary($job['salary'] ?? null);
        $penaltyTerms = $this->matchedTerms($title, $this->cfg['penalties']['terms']);
        $penalty = $penaltyTerms ? $this->cfg['penalties']['points'] : 0;

        $total = max(0, min(100, $stack + $role + $seniority + $region + $salary - $penalty));

        return [
            'score' => $total,
            'eligible_region' => $eligible,
            'breakdown' => [
                'stack' => $stack,
                'stack_matched' => $matched,
                'role' => $role,
                'seniority' => $seniority,
                'seniority_level' => $seniorityLevel,
                'region' => $region,
                'salary' => $salary,
                'salary_level' => $salaryLevel,
                'penalty' => -$penalty,
                'penalty_terms' => $penaltyTerms,
            ],
        ];
    }

    /** Fit band for seen_jobs.json / admin: high | medium | low. */
    public function band(?int $score): string
    {
        if ($score === null) {
            return 'low';
        }

        return match (true) {
            $score >= $this->cfg['bands']['high'] => 'high',
            $score >= $this->cfg['bands']['medium'] => 'medium',
            default => 'low',
        };
    }

    /** Cross-source identity: same company + same normalized title. Null when company is unknown. */
    public static function dedupeHash(?string $company, ?string $title): ?string
    {
        $c = self::normalize($company ?? '', ['inc', 'llc', 'ltd', 'limited', 'gmbh', 'corp', 'corporation', 'co', 'sa', 'sas', 'srl', 'bv']);
        $t = self::normalize(preg_replace('/\(.*?\)|\[.*?\]/', ' ', $title ?? ''), ['remote', 'hybrid', 'worldwide', 'anywhere', 'latam', 'us', 'usa', 'eu', 'emea']);

        if ($c === '' || $t === '') {
            return null;
        }

        return sha1($c . '|' . $t);
    }

    /** Strip HTML to readable plain text. */
    public static function plainText(?string $html, int $limit = 20000): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        $text = preg_replace('/<(br|\/p|\/li|\/h\d|\/div)[^>]*>/i', "\n", $html);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[ \t\x{00A0}]+/u', ' ', $text);
        $text = trim(preg_replace('/\s*\n\s*/', "\n", $text));

        return mb_substr($text, 0, $limit) ?: null;
    }

    // ── internals ────────────────────────────────────────────────────────

    protected function stackPoints(string $haystack): array
    {
        $points = 0;
        $matched = [];
        foreach ($this->cfg['stack'] as $kw => $pts) {
            if ($this->contains($haystack, $kw)) {
                $points += $pts;
                $matched[] = $kw;
            }
        }

        return [min($points, $this->cfg['stack_cap']), $matched];
    }

    protected function seniority(string $title, string $sourceLevel): array
    {
        $s = $this->cfg['seniority'];
        $level = match (true) {
            $this->hasAny($title, $s['junior_terms']) => 'junior',
            $this->hasAny($title, $s['exec_terms']) => 'exec',
            $this->hasAny($title, $s['senior_terms']) => 'senior',
            $this->hasAny($sourceLevel, $s['junior_terms']) => 'junior',
            $this->hasAny($sourceLevel, $s['senior_terms']) => 'senior',
            default => 'mid',
        };

        return [$level, $s['points'][$level]];
    }

    /** true = can apply from Colombia, false = restricted elsewhere, null = unknown. */
    public function eligibleRegion(?string $location, ?string $description, ?array $timezones = null): ?bool
    {
        $r = $this->cfg['region'];

        foreach ($r['restricted_patterns'] as $pattern) {
            if ($description && preg_match($pattern, $description)) {
                return false;
            }
        }

        $loc = mb_strtolower(trim($location ?? ''));
        if ($loc !== '' && $this->hasAny($loc, $r['ok_terms'])) {
            return true;
        }

        $generic = $loc === '' || preg_match('/^(100% )?remote( ?(job|work|first|ok))?$/', $loc);
        if ($generic) {
            if ($timezones) {
                return in_array((float) $r['utc_offset'], array_map('floatval', $timezones), true);
            }

            return null;
        }

        return false;
    }

    protected function salary(?string $salary): array
    {
        $p = $this->cfg['salary']['points'];
        if (! $salary || ! preg_match_all('/(\d[\d,.]*)\s*(k)?/i', $salary, $m, PREG_SET_ORDER)) {
            return ['unknown', $p['unknown']];
        }

        $max = 0;
        foreach ($m as $hit) {
            $n = (float) str_replace(',', '', $hit[1]);
            if (! empty($hit[2])) {
                $n *= 1000;
            }
            $max = max($max, $n);
        }

        $s = mb_strtolower($salary);
        if (str_contains($s, 'hour') || str_contains($s, '/hr')) {
            $max *= 2000;
        } elseif (str_contains($s, 'month')) {
            $max *= 12;
        }

        if ($max <= 0) {
            return ['unknown', $p['unknown']];
        }

        return $max >= $this->cfg['salary']['annual_threshold'] ? ['good', $p['good']] : ['low', $p['low']];
    }

    protected function hasAny(string $text, array $terms): bool
    {
        return $this->matchedTerms($text, $terms) !== [];
    }

    protected function matchedTerms(string $text, array $terms): array
    {
        return array_values(array_filter($terms, fn ($t) => $this->contains($text, $t)));
    }

    /** Word-boundary match so "java" does not hit "javascript" and "php" does not hit "phpunit"-like noise. */
    protected function contains(string $text, string $term): bool
    {
        $term = trim(mb_strtolower($term));

        return $term !== '' && preg_match('/(?<![a-z0-9])' . preg_quote($term, '/') . '(?![a-z0-9])/u', $text) === 1;
    }

    protected static function normalize(string $s, array $stopWords): string
    {
        $s = mb_strtolower($s);
        $s = preg_replace('/[^a-z0-9+#]+/u', ' ', $s);
        $words = array_filter(explode(' ', $s), fn ($w) => $w !== '' && ! in_array($w, $stopWords, true));

        return implode(' ', $words);
    }
}
