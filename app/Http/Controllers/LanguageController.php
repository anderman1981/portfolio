<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    /**
     * Switch application locale.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'es'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
