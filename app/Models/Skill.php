<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['name', 'category', 'proficiency', 'icon'];

    public $translatable = ['name', 'category'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
