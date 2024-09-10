<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\v1\LoginRequest;
use App\Http\Requests\auth\v1\RegisterUserRequest;
use App\Http\Resources\LoginUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'Invalid username or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->input('email'))->first();
        $token = $user->createToken('apiToken')->accessToken;
        $cookie = cookie('access_token', $token, 3600);

        return response()->json([
            'access_token' => $token,
            'user' => new LoginUserResource($user),
        ])->withCookie($cookie);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = new User($request->only('name', 'email'));
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 4;
        $user->avatar = $user->gravatar;
        $user->save();

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    public function logout()
    {
        // clear cookie
        $cookie = Cookie::forget('access_token');

        // revoke access token
        $user = Auth::guard('api')->user();
        $user->token()->revoke();

        return response()->json([
            'message' => 'logout successfully',
        ], Response::HTTP_NO_CONTENT)->withCookie($cookie);
    }

    public function logoutAll()
    {
        $user = Auth::user();
        $tokens = $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked' => 1]);

        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
    }

    public function test()
    {
        return response()->json('dashboard');
    }
}
