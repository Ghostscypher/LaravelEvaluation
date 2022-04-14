<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoodbyeEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Website $website;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
        $this->subject("You have been unsubscribed from {$website->name}");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.send-goodbye-email');
    }
}
