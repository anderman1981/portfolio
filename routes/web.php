<?php

use App\Http\Controllers\DocumentRedirectController;
use App\Http\Controllers\LanguageController;
use App\Livewire\DocumentViewer;
use App\Livewire\JobApplications;
use App\Livewire\PortfolioView;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', PortfolioView::class)->name('portfolio.index');

// Job Applications & Career Management (Private - Require Auth)
Route::middleware('auth')->group(function () {
    Route::get('/applications', JobApplications::class)->name('applications.index');
    Route::get('/documents', DocumentViewer::class)->name('documents.view');
    Route::get('/documents/{evaluation}/{type}', [DocumentRedirectController::class, 'redirect']);
});

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/download-cv', function () {
    $data = [
        'experiences' => Experience::all(),
        'skills' => Skill::all()->groupBy('category'),
        'education' => Education::all(),
    ];

    $pdf = Pdf::loadView('pdf.cv', $data);

    return $pdf->download('CV_Anderson_Martinez.pdf');
})->name('download.cv');

// Render a tailored application document (CV or cover letter) from Markdown to PDF.
// Usage: /download-doc?type=cv&name=main_leadtech  or  /download-doc?type=cover&name=cover_leadtech_ai_native_developer
Route::get('/download-doc', function (\Illuminate\Http\Request $request) {
    $type = $request->query('type') === 'cover' ? 'cover_letters' : 'cv';
    $name = basename((string) $request->query('name'));      // prevent path traversal
    $path = base_path("{$type}/{$name}.md");

    abort_unless($name && file_exists($path), 404, 'Document not found');

    $html = \Illuminate\Support\Str::markdown(file_get_contents($path));
    $pdf = Pdf::loadView('pdf.document', ['html' => $html]);

    return $pdf->download("{$name}.pdf");
})->name('download.doc');

// Google OAuth for the admin panel
use App\Http\Controllers\GoogleAuthController;
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
