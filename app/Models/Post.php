<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description',
    ];

    // public function subscribers()
    // {
    //     return DB::table('websites')
    //         ->join('subscriptions', 'subscriptions.website_id', '=', 'websites.id')
    //         ->join('users', 'users.id', '=', 'subscriptions.user_id')
    //         ->get();
    // }

    public function subscribers()
    {
        if (! $this->website) {
            return null;
        }

        return $this->website->subscribers();
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
