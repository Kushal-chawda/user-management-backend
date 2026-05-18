<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::query()

            ->when($search, function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })

            ->latest()

            ->paginate(10);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),
        ]);

        return response()->json([

            'message' => 'User created successfully',

            'data' => new UserResource($user),

        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([

            'data' => new UserResource($user),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ) {

        $user->update([

            'name' => $request->name,

            'email' => $request->email,
        ]);

        return response()->json([

            'message' => 'User updated successfully',

            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([

            'message' => 'User deleted successfully',
        ]);
    }
}
