<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Note: the app is served from the /cv subfolder. Livewire injects
     * root-relative URLs (/livewire/...). A rewrite in the domain's
     * public_html/.htaccess maps /livewire/* -> /cv/livewire/* so those
     * requests reach this app. Keeping Livewire's defaults here.
     */
    public function boot(): void
    {
        //
    }
}
