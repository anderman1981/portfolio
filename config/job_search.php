<?php

/*
|--------------------------------------------------------------------------
| Job search pre-scoring (App\Services\JobScorer)
|--------------------------------------------------------------------------
| Cheap, deterministic 0-100 score computed at ingest time from title, tags
| and description. It is a triage filter for /rank — not a fit evaluation.
| Tune weights here when the profile changes; then run `php artisan jobs:rescore`.
|
| Score = stack (max 45) + role/title (max 10) + seniority (max 15)
|       + region (max 20) + salary (max 10) − penalties, clamped 0-100.
*/

return [

    // Keyword => points. Each keyword counts once, matched on word boundaries
    // across title + tags + description. Sum is capped at `stack_cap`.
    'stack' => [
        'laravel' => 12,
        'php' => 10,
        'mysql' => 5,
        'node.js' => 5,
        'nodejs' => 5,
        'react' => 5,
        'angular' => 5,
        'python' => 4,
        'docker' => 4,
        'rest api' => 4,
        'restful' => 4,
        'javascript' => 3,
        'typescript' => 3,
        'aws' => 3,
        'vue' => 3,
        'sql' => 2,
        'postgresql' => 2,
        'livewire' => 3,
        'filament' => 3,
        'yii' => 3,
        'api integration' => 3,
        'automation' => 2,
    ],
    'stack_cap' => 45,

    // Title must contain one of these to get the role bonus.
    'role_terms' => ['full stack', 'full-stack', 'fullstack', 'laravel', 'php', 'backend', 'back-end', 'back end', 'tech lead', 'technical lead'],
    'role_points' => 10,

    // Seniority (matched on title first, then on the source's seniority field).
    'seniority' => [
        'senior_terms' => ['senior', 'sr.', 'sr ', 'lead', 'staff', 'principal'],
        'junior_terms' => ['junior', 'jr.', 'jr ', 'intern', 'internship', 'trainee', 'entry level', 'entry-level', 'graduate'],
        'exec_terms' => ['director', 'vp ', 'vice president', 'head of', 'cto'],
        'points' => ['senior' => 15, 'mid' => 10, 'exec' => 5, 'junior' => 0],
    ],

    // Region eligibility for a candidate based in Colombia (UTC-5).
    'region' => [
        'ok_terms' => ['worldwide', 'anywhere', 'global', 'latam', 'latin america', 'south america', 'americas', 'colombia', 'remote - americas', 'utc-5'],
        // Phrases in the description that restrict who can apply.
        'restricted_patterns' => [
            '/\b(?:must|need to|required to) (?:be )?(?:located|based|reside|residing|live) in the (?:us|u\.s\.|united states|uk|eu)\b/i',
            '/\b(?:us|u\.s\.|uk|eu)[- ]based (?:candidates )?only\b/i',
            '/\b(?:us|usa|u\.s\.|uk|eu|europe|canada) only\b/i',
            '/\bauthori[sz]ed to work in the (?:us|u\.s\.|united states|uk)\b/i',
            '/\bus citizens?(?:hip)? (?:only|required)\b/i',
            '/\bsecurity clearance\b/i',
        ],
        'utc_offset' => -5,
        'points' => ['eligible' => 20, 'unknown' => 10, 'restricted' => 0],
    ],

    // Salary: any annual-looking figure >= threshold (USD-ish) scores full points.
    'salary' => [
        'annual_threshold' => 48000, // candidate floor: USD 4,000/month
        'points' => ['good' => 10, 'unknown' => 5, 'low' => 2],
    ],

    // Title terms that signal a different specialty. Matched on title only.
    'penalties' => [
        'terms' => ['ios', 'android', 'swift', 'kotlin', 'flutter', 'react native', 'java', 'scala', 'ruby', 'rails', 'elixir', 'rust', 'c++', 'embedded', 'firmware', 'salesforce', 'sap ', 'data scientist', 'data engineer', 'machine learning', 'ml engineer', 'qa', 'quality engineer', 'sdet', 'devops', 'test engineer', 'designer', 'sre', 'site reliability', 'security engineer', 'blockchain', 'solidity', 'unity', 'game'],
        'points' => 25,
    ],

    // fit bands used by jobs:export (seen_jobs.json) and the admin.
    'bands' => ['high' => 70, 'medium' => 50],

    // /rank only sends jobs at or above this pre-score to the (expensive) agents.
    'rank_threshold' => 55,
];
