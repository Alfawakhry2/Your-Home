<?php

namespace App\Http\Controllers\Api\filament;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
       // List all users (admin only)
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return UserResource::collection(User::paginate(10));
    }

    // Create new user (admin only)
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,seller,buyer',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->hasFile('image')) {
            $user->update([
                'image' => $request->file('image')->store('user-images')
            ]);
        }

        return new UserResource($user);
    }

    // Show specific user (admin only)
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    // Update user (admin can update anyone, users can update themselves)
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|nullable|string|unique:users,phone,'.$user->id,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'role' => 'sometimes|in:admin,seller,buyer',
            'image' => 'sometimes|image|max:2048',
        ]);

        $updateData = $request->only(['name', 'email', 'phone', 'role']);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->hasFile('image')) {
            $user->update([
                'image' => $request->file('image')->store('user-images')
            ]);
        }

        return new UserResource($user);
    }

    // Delete user (admin only)
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->noContent();
    }
}
