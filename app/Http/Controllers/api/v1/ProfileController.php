<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\profile\v1\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class ProfileController extends Controller
{

    public function user(): JsonResponse
    {
        return response()->json(new UserResource(Auth::user()));
    }
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json(new UserResource(Auth::user()));
    }

    public function destroy()
    {
        $user = User::findOrFail(Auth::id());
        $user->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ], Response::HTTP_NO_CONTENT);
    }
}
