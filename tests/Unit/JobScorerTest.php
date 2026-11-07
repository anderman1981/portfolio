<?php

namespace Tests\Unit;

use App\Services\JobScorer;
use PHPUnit\Framework\TestCase;

class JobScorerTest extends TestCase
{
    protected JobScorer $scorer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scorer = new JobScorer(require __DIR__ . '/../../config/job_search.php');
    }

    public function test_strong_laravel_role_scores_high(): void
    {
        $r = $this->scorer->score([
            'title' => 'Senior Full Stack Developer (Laravel)',
            'description' => 'PHP, Laravel, MySQL, React, Docker, REST API integrations. Remote - LATAM.',
            'location' => 'LATAM',
            'salary' => '$60k - $80k',
        ]);

        $this->assertGreaterThanOrEqual(85, $r['score']);
        $this->assertTrue($r['eligible_region']);
        $this->assertSame('high', $this->scorer->band($r['score']));
    }

    public function test_us_only_role_is_restricted_and_penalised(): void
    {
        $r = $this->scorer->score([
            'title' => 'Senior PHP Developer',
            'description' => 'Laravel. Candidates must be located in the US.',
            'location' => 'Worldwide',
        ]);

        $this->assertFalse($r['eligible_region']);
        $this->assertSame(0, $r['breakdown']['region']);
    }

    public function test_off_profile_specialty_is_penalised(): void
    {
        $r = $this->scorer->score(['title' => 'Senior iOS Engineer (Swift)', 'location' => 'Worldwide']);

        $this->assertLessThan(50, $r['score']);
        $this->assertContains('ios', $r['breakdown']['penalty_terms']);
    }

    public function test_java_does_not_match_javascript(): void
    {
        $r = $this->scorer->score(['title' => 'JavaScript Developer']);

        $this->assertSame([], $r['breakdown']['penalty_terms']);
        $this->assertContains('javascript', $r['breakdown']['stack_matched']);
    }

    public function test_region_rules(): void
    {
        $this->assertTrue($this->scorer->eligibleRegion('Colombia, Mexico', null));
        $this->assertFalse($this->scorer->eligibleRegion('USA Only', null));
        $this->assertFalse($this->scorer->eligibleRegion('United Kingdom', null));
        $this->assertNull($this->scorer->eligibleRegion('Remote', null));
        $this->assertTrue($this->scorer->eligibleRegion(null, null, [-6, -5, -4]));
        $this->assertFalse($this->scorer->eligibleRegion(null, null, [0, 1, 2]));
    }

    public function test_salary_parsing(): void
    {
        $this->assertSame('good', $this->scorer->score(['title' => 'x', 'salary' => '$50-$75 /hour'])['breakdown']['salary_level']);
        $this->assertSame('low', $this->scorer->score(['title' => 'x', 'salary' => '$1,500 / month'])['breakdown']['salary_level']);
        $this->assertSame('unknown', $this->scorer->score(['title' => 'x'])['breakdown']['salary_level']);
    }

    public function test_dedupe_hash_ignores_suffixes_and_remote_tags(): void
    {
        $a = JobScorer::dedupeHash('Acme Inc.', 'Senior Backend Engineer (Remote)');
        $b = JobScorer::dedupeHash('ACME', 'Senior Backend Engineer - Remote, LATAM');

        $this->assertNotNull($a);
        $this->assertSame($a, $b);
        $this->assertNull(JobScorer::dedupeHash('', 'Engineer'));
    }

    public function test_plain_text_strips_html(): void
    {
        $this->assertSame("Hello & welcome\nLine two", JobScorer::plainText('<p>Hello &amp; welcome</p><p>Line two</p>'));
        $this->assertNull(JobScorer::plainText('  '));
    }
}
