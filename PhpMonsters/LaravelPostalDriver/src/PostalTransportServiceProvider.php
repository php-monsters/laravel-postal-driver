<?php

namespace PhpMonsters\LaravelPostalDriver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use GuzzleHttp\Client;

class PostalTransportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(MailManager::class)->extend('postal', function ($config) {
            return new PostalTransport(
                new Client(),
                $config['api_key'],
                $config['base_url']
            );
        });
    }
}
