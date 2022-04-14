<?php

namespace App\Jobs;

use App\Mail\UserPostEmail;
use App\Models\Post;
use App\Models\User;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPostToUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public User $user;
    public Website $website;
    public Post $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $sent_post = $this->user->sentPosts()->where([
                'post_id' => $this->post->id,
                'website_id' => $this->post->website_id,
            ])->firstOrCreate([
                'post_id' => $this->post->id,
                'website_id' => $this->post->website_id,
            ]);

            if ($sent_post->email_sent) {
                return;
            }

            Mail::to($this->user)
                ->send(new UserPostEmail($this->post));

            $sent_post->email_sent = true;
            $sent_post->save();

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();

            Log::error($th);
        }
    }
}
