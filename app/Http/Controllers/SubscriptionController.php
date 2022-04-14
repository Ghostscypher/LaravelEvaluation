<?php

namespace App\Http\Controllers;

use App\Events\UserSubscribed;
use App\Events\UserUnsubscribed;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return apiResponse(Subscription::with(['user', 'website'])->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Website $website)
    {
        $subscription = $user->subscriptions()->where('website_id', $website->id)->first();

        if ($subscription) {
            return apiResponse(null, 400, ["Already subscribed"], "You are already subscribed to this website");
        }

        try {
            $user->subscriptions()->create(['website_id' => $website->id]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to subscribe."]);
        }

        event(new UserSubscribed($user, $website));

        return apiResponse(null, 200, [], "Subscribed to posts from '{$website->name}'");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $subscription_id)
    {
        return apiResponse($user->subscriptions()->with('website')->find($subscription_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Website $website)
    {
        $subscription = $user->subscriptions()->where('website_id', $website->id)->first();

        if (! $subscription) {
            return apiResponse(null, 404, ["Subscription not found."], "Not found");
        }

        try {
            $subscription->delete();
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to unsubscribe."]);
        }

        event(new UserUnsubscribed($user, $website));

        return apiResponse(null, 200, [], sprintf("Unsubscribed to posts from '%s'", $subscription->website->name));
    }

    public function getSubscriptions(User $user)
    {
        return apiResponse($user->subscriptions()->with('website')->paginate());
    }
}
