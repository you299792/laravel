<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users via API.
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created user via API.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    /**
     * Display the specified user via API.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified user via API.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified user via API.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        $this->renumberIds();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * Renumber user IDs to keep them continuous.
     */
    private function renumberIds()
    {
        $users = User::orderBy('id')->get();
        
        foreach ($users as $index => $user) {
            $user->timestamps = false;
            $user->id = $index + 1;
            $user->save();
        }

        // Reset auto-increment counter
        $lastId = User::max('id') ?? 0;
        \DB::statement('ALTER TABLE users AUTO_INCREMENT = ' . ($lastId + 1));
    }

    /**
     * Verify user email via API.
     */
    public function verify(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        return response()->json(['message' => 'Email verified successfully', 'user' => $user], 200);
    }
}
