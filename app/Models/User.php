<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow the whitelisted admin emails (same list Google login uses),
        // falling back to the internal @anderson.com domain.
        $allowed = collect(explode(',', (string) env('ADMIN_ALLOWED_EMAILS', '')))
            ->map(fn ($e) => trim(strtolower($e)))
            ->filter();

        if ($allowed->isNotEmpty() && $allowed->contains(strtolower($this->email))) {
            return true;
        }

        return str_ends_with($this->email, '@anderson.com');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
