<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\GroupCreatedEvent;
use App\Listeners\SendGroupCreationNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'App\Events\SendOpozoriloEvent' => [
            'App\Listeners\SendOpozorilo',
        ],
        GroupCreatedEvent::class => [
            SendGroupCreationNotification::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
