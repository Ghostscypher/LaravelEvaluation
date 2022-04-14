<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {
    // We might use apiResources but since we are interested in particlar endpoints then we will have to explicitly
    // specify the endpoints

    Route::apiResource('users', UserController::class);

    Route::apiResource('websites', WebsiteController::class);

    Route::get('posts', [PostController::class, 'getAllPosts']);
    Route::apiResource('websites.posts', PostController::class);

    Route::get('subscriptions', [SubscriptionController::class, 'index']);

    Route::get('users/{user}/subscriptions', [SubscriptionController::class, 'getSubscriptions']);
    Route::get('users/{user}/subscriptions/{subscription}', [SubscriptionController::class, 'show']);

    Route::post('subscribe/users/{user}/websites/{website}', [SubscriptionController::class, 'store']);
    Route::post('unsubscribe/users/{user}/websites/{website}', [SubscriptionController::class, 'destroy']);

});
