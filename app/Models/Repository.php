<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Repository extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['name', 'url', 'description', 'category', 'technologies', 'stars', 'sort_order', 'is_visible'];

    public $translatable = ['name', 'description', 'category'];

    protected $casts = [
        'technologies' => 'array',
        'is_visible' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
