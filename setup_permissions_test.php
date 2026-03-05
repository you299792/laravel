<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "Setting up test data for permissions...\n\n";

// Reset cache
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

// Clear old data
echo "Clearing old data...\n";
\DB::table('model_has_roles')->truncate();
\DB::table('model_has_permissions')->truncate();
\DB::table('role_has_permissions')->truncate();
Role::query()->delete();
Permission::query()->delete();
echo "✓ Old data cleared\n\n";

// Create permissions
echo "Creating permissions...\n";
Permission::firstOrCreate(['name' => 'edit posts', 'guard_name' => 'sanctum']);
Permission::firstOrCreate(['name' => 'delete posts', 'guard_name' => 'sanctum']);
Permission::firstOrCreate(['name' => 'view posts', 'guard_name' => 'sanctum']);
echo "✓ Permissions created\n\n";

// Create roles
echo "Creating roles...\n";
$adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
$adminRole->syncPermissions(Permission::all());
echo "✓ Admin role (all permissions)\n";

$userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'sanctum']);
$userRole->syncPermissions(['view posts']);
echo "✓ User role (view posts only)\n\n";

// Create test users
echo "Creating test users...\n";
$admin = User::firstOrCreate(
    ['email' => 'admin@test.com'],
    ['name' => 'Admin User', 'password' => bcrypt('password'), 'email_verified_at' => now()]
);
// Delete old role assignments
\DB::table('model_has_roles')->where('model_id', $admin->id)->delete();
// Assign new role
$adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();
$admin->roles()->attach($adminRole);
echo "✓ admin@test.com / password (admin role)\n";

$normalUser = User::firstOrCreate(
    ['email' => 'user@test.com'],
    ['name' => 'Normal User', 'password' => bcrypt('password'), 'email_verified_at' => now()]
);
// Delete old role assignments
\DB::table('model_has_roles')->where('model_id', $normalUser->id)->delete();
// Assign new role
$userRole = Role::where('name', 'user')->where('guard_name', 'sanctum')->first();
$normalUser->roles()->attach($userRole);
echo "✓ user@test.com / password (user role)\n\n";

echo "=== Setup Complete! ===\n\n";
echo "Test credentials:\n";
echo "  Admin: admin@test.com / password\n";
echo "  User:  user@test.com / password\n\n";
echo "Test endpoints:\n";
echo "  GET /api/permissions/check - View roles/permissions\n";
echo "  GET /api/posts/edit-test - Requires 'edit posts' permission\n";
echo "  GET /api/admin/test - Requires 'admin' role\n";
