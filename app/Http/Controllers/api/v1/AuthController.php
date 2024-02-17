<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\v1\LoginRequest;
use App\Http\Requests\auth\v1\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'Invalid username or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->input('email'))->first();
        $token = $user->createToken('admin')->accessToken;
        $cookie = cookie('jwt', $token, 3600);

        return response()->json([
            'access_token' => $token
        ])->withCookie($cookie);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create(
            $request->only('name', 'email')
            + ['password' => Hash::make($request->input('password'))]
        );

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response()->json([
            'success' => 'logout successfully'
        ], Response::HTTP_NO_CONTENT)->withCookie($cookie);
    }

    public function test() {

        return response()->json('dashboard');
        // return response()->json(Auth::id());
    }
}
