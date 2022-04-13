<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Website $website)
    {
        return apiResponse($website->posts()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Website $website)
    {
        $validator = validator($request->all(), [
            'title' => ['bail', 'required', 'string', 'max:100'],
            'description' => ['bail', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        try {
            $post = $website->posts()->create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create website."]);
        }

        return apiResponse($post, 200, [], "Post created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website, $post_id)
    {
        $post = $website->posts()->find($post_id);

        if(!$post){
            return apiResponse(null, 404, ["Post not found."], "Not found");
        }

        return apiResponse($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website, $post_id)
    {
        $validator = validator($request->all(), [
            'title' => ['bail', 'required', 'string', 'max:100'],
            'description' => ['bail', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        $post = $website->posts()->find($post_id);

        if(!$post){
            return apiResponse(null, 404, ["Post not found."], "Not found");
        }

        try {
            $post->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create website."]);
        }

        return apiResponse($post, 200, [], "Post updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website, $post_id)
    {
        $post = $website->posts()->find($post_id);

        if(!$post){
            return apiResponse(null, 404, ["Post not found."], "Not found");
        }

        try {
            $post->delete();
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create website."]);
        }

        return apiResponse(null, 200, [], "Post deleted");
    }

    public function getAllPosts()
    {
        return apiResponse(Post::with(['website'])->paginate());
    }
}
