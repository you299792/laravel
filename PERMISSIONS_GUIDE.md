# Spatie Laravel Permissions - Quick Guide

## ✅ Installation Complete

Your Laravel application now has Spatie Laravel Permissions installed and configured.

## Usage Examples

### Creating Roles and Permissions

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create permissions
Permission::create(['name' => 'edit posts']);
Permission::create(['name' => 'delete posts']);
Permission::create(['name' => 'publish posts']);
Permission::create(['name' => 'view posts']);

// Create roles
$role = Role::create(['name' => 'admin']);
$role = Role::create(['name' => 'editor']);
$role = Role::create(['name' => 'user']);

// Assign permissions to roles
$adminRole = Role::findByName('admin');
$adminRole->givePermissionTo(['edit posts', 'delete posts', 'publish posts', 'view posts']);

$editorRole = Role::findByName('editor');
$editorRole->givePermissionTo(['edit posts', 'view posts']);
```

### Assigning Roles to Users

```php
use App\Models\User;

$user = User::find(1);

// Assign role to user
$user->assignRole('admin');

// Assign multiple roles
$user->assignRole(['editor', 'user']);

// Assign permissions directly to user
$user->givePermissionTo('edit posts');
```

### Checking Permissions

```php
// Check if user has permission
if ($user->can('edit posts')) {
    // User has permission
}

// Check if user has role
if ($user->hasRole('admin')) {
    // User is admin
}

// Check if user has any of the roles
if ($user->hasAnyRole(['admin', 'editor'])) {
    // User is admin or editor
}

// Check if user has all roles
if ($user->hasAllRoles(['admin', 'editor'])) {
    // User has both roles
}
```

### Using in Controllers

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function update(Request $request, Post $post)
    {
        // Check permission
        if (!auth()->user()->can('edit posts')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Update post logic
        $post->update($request->all());
        
        return response()->json($post);
    }
    
    public function destroy(Post $post)
    {
        // Using authorize method
        $this->authorize('delete posts');
        
        $post->delete();
        
        return response()->json(['message' => 'Post deleted']);
    }
}
```

### Using Middleware

Add to your routes:

```php
// Protect route with permission
Route::middleware(['auth:sanctum', 'permission:edit posts'])
    ->put('/posts/{post}', [PostController::class, 'update']);

// Protect route with role
Route::middleware(['auth:sanctum', 'role:admin'])
    ->delete('/posts/{post}', [PostController::class, 'destroy']);

// Multiple permissions (user needs ALL)
Route::middleware(['auth:sanctum', 'permission:edit posts|delete posts'])
    ->group(function () {
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

// Multiple roles (user needs ONE)
Route::middleware(['auth:sanctum', 'role:admin|editor'])
    ->get('/dashboard', [DashboardController::class, 'index']);
```

### Using in Blade (if you add web routes)

```php
@can('edit posts')
    <button>Edit Post</button>
@endcan

@role('admin')
    <button>Admin Panel</button>
@endrole

@hasanyrole('admin|editor')
    <button>Content Management</button>
@endhasanyrole
```

### API Response Example

```php
// Get user with roles and permissions
Route::get('/user', function (Request $request) {
    $user = $request->user();
    
    return response()->json([
        'user' => $user,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name'),
    ]);
})->middleware('auth:sanctum');
```

### Seeding Roles and Permissions

Create a seeder:

```bash
php artisan make:seeder RolePermissionSeeder
```

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo(['view posts', 'create posts', 'edit posts']);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view posts']);

        // Assign admin role to first user
        $admin = User::first();
        if ($admin) {
            $admin->assignRole('admin');
        }
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

## Configuration

The config file is at: `config/permission.php`

Key settings:
- `models`: Customize Role and Permission models
- `table_names`: Customize table names
- `cache`: Configure permission caching

## Important Notes

1. **Cache**: Permissions are cached. Clear cache after changes:
   ```bash
   php artisan permission:cache-reset
   ```

2. **Guard Names**: Default guard is 'web'. For API with Sanctum, permissions work with the Sanctum guard.

3. **Direct Permissions**: You can assign permissions directly to users without roles.

4. **Multiple Guards**: You can have different permissions for different guards (web, api, etc.).

## Documentation

Full documentation: https://spatie.be/docs/laravel-permission/
