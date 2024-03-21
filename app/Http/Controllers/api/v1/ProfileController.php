<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\file\ImageRequest;
use App\Http\Requests\profile\v1\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class ProfileController extends Controller
{

    public function me(): JsonResponse
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

    public function storeAvatar(ImageRequest $request)
    {
        $file_name = date('Ymd_His_').$request->img_file->getClientOriginalName();
        $file_path = storage_path('app\\public\\profiles\\' . $file_name);
        $user = User::find(Auth::user()->id);
        $user->avatar = $file_path;
        $user->save();
        
        $request->img_file->move(storage_path('app\\public\\profiles\\' . $user->id), $file_name);

        return response()->json(['success' => 'upload avatar successfully']);

    }
}
