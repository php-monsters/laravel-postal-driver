<?php

namespace PhpMonsters\LaravelPostalDriver;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class PostalTransportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(MailManager::class)->extend('postal', function ($config) {
            return new PostalTransport(
                $config['key'],
                $config['endpoint'] ?? 'https://postal.yourdomain.com'
            );
        });
    }
}