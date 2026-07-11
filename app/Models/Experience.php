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

    /** Format an ISO date (YYYY-MM-DD) into a localized "Mon YYYY" label. */
    protected function labelFor(?string $date): ?string
    {
        if (!$date) {
            return null;
        }
        try {
            return ucfirst(\Carbon\Carbon::parse($date)->locale(app()->getLocale())->isoFormat('MMM YYYY'));
        } catch (\Throwable $e) {
            return $date;
        }
    }

    public function getStartLabelAttribute(): ?string
    {
        return $this->labelFor($this->start_date);
    }

    public function getEndLabelAttribute(): ?string
    {
        return $this->labelFor($this->end_date);
    }
}
