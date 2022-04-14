<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_id', 'website_id',
    ];

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
