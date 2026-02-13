<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        $this->renumberIds();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
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
     * Verify user email.
     */
    public function verify(User $user)
    {
        $user->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.show', $user)->with('success', 'Email verified successfully');
    }
}
