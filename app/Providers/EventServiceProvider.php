<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\RequestEvent::class => [
            \App\Listeners\RequestListener::class,
        ],
        \App\Events\ExceptionEvent::class => [
            \App\Listeners\ExceptionListener::class,
        ],
    ];
}
