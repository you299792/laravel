<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/**
 * Authentication Routes
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * Protected Routes (require authentication)
 */
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User routes
    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/verify', [UserController::class, 'verify'])->name('users.verify');

    // Get current authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Test routes for permissions
    Route::get('/permissions/check', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'checks' => [
                'has_admin_role' => $user->hasRole('admin'),
                'can_edit_posts' => $user->can('edit posts'),
                'can_delete_posts' => $user->can('delete posts'),
            ]
        ]);
    });

    // Protected by permission - only users with 'edit posts' can access
    Route::middleware('permission:edit posts')->get('/posts/edit-test', function () {
        return response()->json(['message' => 'Success! You have permission to edit posts.']);
    });

    // Protected by role - only admin users can access
    Route::middleware('role:admin')->get('/admin/test', function () {
        return response()->json(['message' => 'Success! You have admin role.']);
    });
});
