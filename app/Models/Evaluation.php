<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'external_id',
        'rank',
        'evaluation_date',
        'company',
        'position',
        'score',
        'status',
        'jd_url',
        'archetype',
        'domain',
        'function',
        'requirements',
        'match_analysis',
        'level_strategy',
        'compensation_analysis',
        'interview_prep',
        'legitimacy_assessment',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'requirements' => 'array',
        'match_analysis' => 'array',
        'level_strategy' => 'array',
        'compensation_analysis' => 'array',
        'interview_prep' => 'array',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function coverLetter()
    {
        return $this->documents()->where('type', 'cover')->first();
    }

    public function summary()
    {
        return $this->documents()->where('type', 'summary')->first();
    }

    public function report()
    {
        return $this->documents()->where('type', 'report')->first();
    }

    public function getScoreNumAttribute(): ?float
    {
        if (!$this->score) return null;
        return (float)preg_replace('/[^0-9.]/', '', $this->score);
    }
}
