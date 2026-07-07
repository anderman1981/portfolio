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
