<?php

namespace App\Events;

use App\Models\User;
use App\Models\Website;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUnsubscribed
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public User $user;
    public Website $website;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
