<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DocumentRedirectController extends Controller
{
    public function redirect(int $evaluation, string $type): RedirectResponse
    {
        return redirect(route('documents.view', [
            'evaluation' => $evaluation,
            'type' => $type,
        ]));
    }
}
