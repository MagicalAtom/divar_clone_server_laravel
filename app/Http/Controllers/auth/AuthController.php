<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if ($validation->fails()) {
            //Show errors
            return response($validation->errors(),)->setStatusCode(401);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => \Hash::make($request->get('password'))
        ]);

        $token = $user->createToken($user->name . '-sanjagh')->plainTextToken;

        return response()->json([
            'data' => [
                'username' => $user->name,
                'email' => $user->email,
                'date' => Carbon::now()
            ],
            'token' => $token,
            'message' => 'user created',
        ])->setStatusCode(200);

    }

    public function login(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        if ($validation->fails()) {
            return response()->json(
                $validation->errors(), 401
            );
        }

        $user = User::where('email', $request->get('email'))->first();
        if (!$user || !\Hash::check($request->get('password'), $user->password)) {
            return response()->json(
                [
                    'message' => 'User Invalid Credentials'
                ]
                , 401);
        }

        $token = $user->createToken($user->name . '-sanjagh')->plainTextToken;

        return response()->json([
            'data' => [
                'username' => $user->name,
                'email' => $user->email,
                'date' => Carbon::now()
            ],
            'token' => $token,
            'message' => 'user found',
        ])->setStatusCode(200);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'logout'
        ])->setStatusCode(200);
    }



    public function user(Request $request){
        dd(\Auth::user());
    }
}
