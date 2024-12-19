<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [

        Login::class => [
            SetTenantIdInSession::class,
        ]

    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
