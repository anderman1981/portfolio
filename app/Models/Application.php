<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'evaluation_id',
        'company',
        'position',
        'application_date',
        'status',
        'score',
        'link',
        'notes',
        'interview_date_unix',
        'offer_date_unix',
    ];

    protected $casts = [
        'application_date' => 'date',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function coverLetter()
    {
        return $this->evaluation?->coverLetter();
    }

    public function summary()
    {
        return $this->evaluation?->summary();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Postulado' => 'secondary',
            'En Revisión' => 'info',
            'Entrevista' => 'primary',
            'Prueba Técnica' => 'warning',
            'Oferta' => 'success',
            'Rechazado' => 'danger',
            'Aceptado' => 'success',
            default => 'secondary',
        };
    }

    public function getScoreNumAttribute(): ?float
    {
        if (!$this->score) return null;
        return (float)preg_replace('/[^0-9.]/', '', $this->score);
    }
}
