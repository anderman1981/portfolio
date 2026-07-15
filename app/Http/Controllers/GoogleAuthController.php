<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect('/amrTechAdmin/login')->withErrors(['email' => 'No se pudo autenticar con Google.']);
        }

        // Uses config() — safe after config:cache. env() would return null in production.
        $allowed = collect(config('services.admin.allowed_emails', []))
            ->map(fn ($e) => strtolower($e));

        if ($allowed->isNotEmpty() && ! $allowed->contains(strtolower($googleUser->getEmail()))) {
            abort(403, 'Este correo no está autorizado para el panel.');
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: 'Admin',
                'password' => bcrypt(Str::random(32)), // random; login is via Google
            ]
        );

        Auth::login($user, remember: true);

        return redirect('/amrTechAdmin');
    }
}
