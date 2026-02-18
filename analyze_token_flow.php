<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Str;

echo "=== SANCTUM TOKEN AUTHENTICATION FLOW ANALYSIS ===\n\n";

// Simulate what happens when Thunder Client sends a request
echo "Step 1: Get the latest token from database\n";
$tokenRecord = DB::table('personal_access_tokens')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$tokenRecord) {
    echo "❌ No tokens in database!\n";
    exit;
}

echo "Token ID: {$tokenRecord->id}\n";
echo "Token (hashed, first 20 chars): " . substr($tokenRecord->token, 0, 20) . "...\n";
echo "Full token length: " . strlen($tokenRecord->token) . " chars\n";
echo "User ID: {$tokenRecord->tokenable_id}\n\n";

// Show what the Authorization header should look like
echo "Step 2: What YOU need to send in Thunder Client\n";
echo "The plain token from login/register response should look like:\n";
echo "  Format: {$tokenRecord->id}|SomeLongRandomString\n";
echo "  Example: {$tokenRecord->id}|lpqW2DYzaOTuxOUPXUiHhrtyfkPq74KbcWcQ7tKzaa567dbb\n\n";

echo "Step 3: How Sanctum validates the token\n";
echo "When you send: Authorization: Bearer {$tokenRecord->id}|PlainTextToken\n";
echo "Sanctum does:\n";
echo "  1. Splits token by '|' to get ID and plaintext\n";
echo "  2. Hashes the plaintext using SHA-256\n";
echo "  3. Looks up token record where id={$tokenRecord->id} AND token=hash\n";
echo "  4. If found, loads the user\n\n";

// Test with a sample token
echo "Step 4: Testing token hash validation\n";
$samplePlainToken = Str::random(40);
$sampleHash = hash('sha256', $samplePlainToken);

echo "Sample plain token: {$samplePlainToken}\n";
echo "Sample hashed token: {$sampleHash}\n";
echo "Hash length: " . strlen($sampleHash) . " chars\n\n";

echo "Database token hash length: " . strlen($tokenRecord->token) . " chars\n";
if (strlen($tokenRecord->token) === 64 && strlen($sampleHash) === 64) {
    echo "✓ Token hash lengths match (64 chars = SHA-256)\n";
} else {
    echo "❌ Token hash length mismatch!\n";
}

echo "\n=== CHECKING MIDDLEWARE REGISTRATION ===\n\n";

// Check app configuration
$request = Illuminate\Http\Request::create('/api/me', 'GET');
$request->headers->set('Accept', 'application/json');
$request->headers->set('Authorization', 'Bearer ' . $tokenRecord->id . '|test');

echo "Simulated request:\n";
echo "  URL: {$request->getUri()}\n";
echo "  Method: {$request->getMethod()}\n";
echo "  Headers:\n";
echo "    Accept: {$request->headers->get('Accept')}\n";
echo "    Authorization: {$request->headers->get('Authorization')}\n\n";

// Check if route has sanctum middleware
$route = app('router')->getRoutes()->match($request);
echo "Matched route: {$route->uri()}\n";
echo "Route middleware: " . implode(', ', $route->gatherMiddleware()) . "\n\n";

// Check auth guards
echo "=== AUTH CONFIGURATION ===\n\n";
$guards = config('auth.guards');
foreach ($guards as $name => $config) {
    echo "Guard '{$name}':\n";
    echo "  Driver: {$config['driver']}\n";
    if (isset($config['provider'])) {
        echo "  Provider: {$config['provider']}\n";
    }
    echo "\n";
}

echo "Default guard: " . config('auth.defaults.guard') . "\n\n";

echo "=== RECOMMENDATION ===\n";
echo "1. Make sure Laravel server is running: php artisan serve\n";
echo "2. Do a fresh login: POST http://127.0.0.1:8000/api/login\n";
echo "3. Copy the ENTIRE token from response (including the number before |)\n";
echo "4. In Thunder Client, set header:\n";
echo "   Authorization: Bearer {ENTIRE_TOKEN_HERE}\n";
echo "5. Make sure to include 'Accept: application/json' header\n";
echo "6. Try GET http://127.0.0.1:8000/api/me\n";
