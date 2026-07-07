<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['title', 'description', 'technologies', 'url', 'image_path'];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'technologies' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
