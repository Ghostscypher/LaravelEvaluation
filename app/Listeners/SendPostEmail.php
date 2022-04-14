<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Jobs\SendPostToUser;

class SendPostEmail
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
     * @param  \App\Events\PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $subscribers = $event->post->subscribers()->get();

        // If there are no subscribers because the website has been deleted
        // do not continue
        if (! $subscribers) {
            return;
        }

        foreach ($subscribers as $subscriber) {
            dispatch(new SendPostToUser($subscriber, $event->post));
        }
    }
}
