<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make($request->password)
        ]);

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        Mail::to($user->email)->send(new WelcomeUserMail($user));

        return response()->json([

            'message' => 'Register successful',

            'token' => $token,

            'user' => $user
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(
            'email',
            'password'
        );

        if (!Auth::attempt($credentials)) {

            return response()->json([

                'message' => 'Invalid credentials'

            ], 401);
        }

        $user = Auth::user();

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return response()->json([

            'message' => 'Login successful',

            'token' => $token,

            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->tokens()
            ->delete();

        return response()->json([

            'message' => 'Logout successful'
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
