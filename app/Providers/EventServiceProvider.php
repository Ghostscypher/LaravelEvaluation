<?php

namespace App\Providers;

use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Events\UserSubscribed;
use App\Events\UserUnsubscribed;
use App\Listeners\SendGoodbyeEmail;
use App\Listeners\SendPostEmail;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserSubscribed::class => [
            SendWelcomeEmail::class,
        ],
        UserUnsubscribed::class => [
            SendGoodbyeEmail::class,
        ],
        PostCreated::class => [
            SendPostEmail::class,
        ],
        PostUpdated::class => [
            // No listeners currently defined
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
