<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;

class LanguageNegotiation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Manual selection from session
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));

            return $next($request);
        }

        // 2. GeoIP Detection
        $ip = $request->ip();
        if ($ip === '127.0.0.1' || $ip === '::1') {
            $ip = '186.155.210.134'; // Sample Colombian IP for local testing
        }

        $position = Location::get($ip);

        $spanishCountries = ['CO', 'ES', 'MX', 'AR', 'CL', 'PE', 'VE', 'EC', 'GT', 'CU', 'BO', 'DO', 'HN', 'PY', 'SV', 'NI', 'CR', 'UY', 'PA'];

        if ($position && in_array($position->countryCode, $spanishCountries)) {
            app()->setLocale('es');

            return $next($request);
        }

        // 3. Browser Language Fallback
        $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if ($browserLang === 'es') {
            app()->setLocale('es');
        } else {
            app()->setLocale('en');
        }

        return $next($request);
    }
}
