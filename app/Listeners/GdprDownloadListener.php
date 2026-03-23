<?php

namespace App\Listeners;

use Soved\Laravel\Gdpr\Events\GdprDownloaded;

class GdprDownloadListener
{
    public function handle(GdprDownloaded $event)
    {
        \Log::info('User downloaded GDPR data', [
            'user_id' => $event->user->id
        ]);
    }
}