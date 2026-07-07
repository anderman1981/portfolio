<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Education extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['degree', 'institution', 'year'];

    public $translatable = ['degree', 'institution'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
