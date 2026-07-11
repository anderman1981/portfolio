<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $fillable = [
        'source', 'external_id', 'title', 'company', 'url', 'salary',
        'job_type', 'location', 'tags', 'published_at',
        'is_favorite', 'is_applied', 'is_dismissed', 'apply_queued',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_favorite' => 'boolean',
        'is_applied' => 'boolean',
        'is_dismissed' => 'boolean',
        'apply_queued' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeQueued($query)
    {
        return $query->where('apply_queued', true);
    }
}
