<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SetLocationIdInSession;

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
            SetLocationIdInSession::class,
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
