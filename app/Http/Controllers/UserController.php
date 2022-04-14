<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return apiResponse(User::paginate());
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
            'email' => ['bail', 'required', 'email', 'unique:users,email'],
            'name' => ['bail', 'required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random()),
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to create user."]);
        }

        return apiResponse($user, 200, [], "User created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return apiResponse($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = validator($request->all(), [
            'email' => ['bail', 'required', 'email', 'unique:users,email'],
            'name' => ['bail', 'required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return apiResponse(null, 422, $validator->errors(), "Validation error");
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to update user."]);
        }

        return apiResponse($user, 200, [], "User updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Throwable $th) {
            Log::error($th);

            return apiResponse(null, 500, ["An unknown error occurred while trying to delete user."]);
        }

        return apiResponse(null, 200, [], "User deleted");
    }
}
