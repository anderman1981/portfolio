<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['company', 'role', 'location', 'start_date', 'end_date', 'achievements', 'is_current'];

    public $translatable = ['role', 'location', 'achievements'];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
