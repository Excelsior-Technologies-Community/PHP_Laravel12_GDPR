<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Soved\Laravel\Gdpr\Events\GdprDownloaded;
use App\Listeners\GdprDownloadListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(GdprDownloaded::class, GdprDownloadListener::class);
    }
}