<?php

namespace App\Console\Commands;

use App\Jobs\SendPostToUser;
use App\Models\Website;
use Illuminate\Console\Command;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send
                            {--website= : Name or Id of the website to send subscribers email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to subscribers of posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $website = $this->option('website');

        $websites = Website::with('subscribers', 'posts')
                    ->when($website, function ($query) use ($website) {
                        return $query->whereName($website)->orWhere('id', $website);
                    })->get();

        foreach ($websites as $website) {
            foreach ($website->subscribers as $subscriber) {
                foreach ($website->posts as $post) {
                    dispatch_sync(new SendPostToUser($subscriber, $post));
                }
            }
        }

        return 0;
    }
}
