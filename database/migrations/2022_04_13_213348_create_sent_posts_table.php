<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Website::class)->references('id')->on('websites')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Post::class)->references('id')->on('posts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_posts');
    }
}
