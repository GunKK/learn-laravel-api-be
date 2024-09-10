<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\v1\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->whereNotIn('id', [Auth::id()])->paginate();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        // dd($request->all());
        $user = new User($request->validated());
        $user->password = Hash::make('default_password');
        $user->role_id = $request->role_id ?? 4;
        $user->save();

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->role_id = $request->input('role_id');
        $user->save();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
