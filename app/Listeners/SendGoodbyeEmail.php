<?php

namespace App\Listeners;

use App\Events\UserUnsubscribed;
use App\Mail\GoodbyeEmail;
use Illuminate\Support\Facades\Mail;

class SendGoodbyeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserUnsubscribed  $event
     * @return void
     */
    public function handle(UserUnsubscribed $event)
    {
        Mail::to($event->user)->send(new GoodbyeEmail($event->user, $event->website));
    }
}
