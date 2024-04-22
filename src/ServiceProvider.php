<?php

namespace Henriquex25\LaravelAuditor;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/audit.php', 'audit');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);
    }
}