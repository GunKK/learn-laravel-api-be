<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\v1\LoginRequest;
use App\Http\Requests\auth\v1\RegisterUserRequest;
use App\Http\Resources\LoginUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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
        $token = $user->createToken('apiToken')->accessToken;
        $cookie = cookie('accessToken', $token, 3600);

        return response()->json([
            'accessToken' => $token,
            'user' => new LoginUserResource($user)
        ])->withCookie($cookie);

        // $email = $request->input('email');
        // $password = $request->input('password');

        // $response = Http::asForm()->post(config('services.passport.login_endpoint'), [
        //     'client_id' => config('services.passport.client_fe_id'),
        //     'client_secret' => config('services.passport.client_fe_secret'),
        //     'grant_type' => 'password',
        //     'username' => $email,
        //     'password' => $password,
        // ]);

        // return $response->json();
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
        // clear cookie
        $cookie = Cookie::forget('accessToken');

        // revoke access token
        $user = Auth::guard('api')->user();
        $user->token()->revoke();

        return response()->json([
            'message' => 'logout successfully'
        ], Response::HTTP_NO_CONTENT)->withCookie($cookie);
    }

    public function logoutAll() {
        $user = Auth::user();
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked'=> 1]);

        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
    }

    public function test() {
        return response()->json('dashboard');
    }
}
