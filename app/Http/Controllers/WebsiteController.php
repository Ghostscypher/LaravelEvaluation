<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return apiResponse(Website::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => ['bail', 'required', 'string', 'max:30', 'unique:websites,name'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        try {
            $website = Website::create([
                'name' => $request->name,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create website."]);
        }

        return apiResponse($website, 200, [], "Website created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        return apiResponse($website);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        $validator = validator($request->all(), [
            'name' => ['bail', 'required', 'string', 'max:30', 'unique:websites,name'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        try {
            $website->update([
                'name' => $request->name,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create website."]);
        }

        return apiResponse($website, 200, [], "Website updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        try {
            $website->delete();
        } catch (\Throwable $th) {
            throw $th;
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to delete the website."]);
        }

        return apiResponse(null, 200, [], "Website deleted");
    }
}
