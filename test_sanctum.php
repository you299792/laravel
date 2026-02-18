<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TESTING SANCTUM TOKEN AUTHENTICATION ===\n\n";

// Get the latest token
$tokenRecord = DB::table('personal_access_tokens')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$tokenRecord) {
    echo "❌ No tokens found. Please login first.\n";
    exit;
}

echo "Token ID: {$tokenRecord->id}\n";
echo "User ID: {$tokenRecord->tokenable_id}\n\n";

// Try to find the user using Sanctum
$user = DB::table('users')->find($tokenRecord->tokenable_id);

if ($user) {
    echo "✓ User found: {$user->email}\n";
} else {
    echo "❌ User not found!\n";
}

// Check if Laravel Sanctum is installed
echo "\nSanctum Installation Check:\n";
if (class_exists('Laravel\Sanctum\Sanctum')) {
    echo "✓ Laravel Sanctum package is installed\n";
} else {
    echo "❌ Laravel Sanctum package NOT found!\n";
}

if (class_exists('Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful')) {
    echo "✓ Sanctum middleware classes exist\n";
} else {
    echo "❌ Sanctum middleware classes NOT found!\n";
}

// Check for HasApiTokens trait
echo "\nUser Model Check:\n";
$userModel = new App\Models\User();
$traits = class_uses($userModel);
if (in_array('Laravel\Sanctum\HasApiTokens', $traits)) {
    echo "✓ User model has HasApiTokens trait\n";
} else {
    echo "❌ User model missing HasApiTokens trait!\n";
}

// Check guard configuration
echo "\nAuth Configuration:\n";
$guards = config('auth.guards');
echo "Available guards: " . implode(', ', array_keys($guards)) . "\n";

if (isset($guards['sanctum'])) {
    echo "✓ Sanctum guard exists\n";
    echo "  Driver: " . $guards['sanctum']['driver'] . "\n";
} else {
    echo "❌ Sanctum guard NOT configured!\n";
    echo "  This might be the problem - you need a sanctum guard!\n";
}

echo "\n";
