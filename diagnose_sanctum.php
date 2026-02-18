<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SANCTUM TOKEN DIAGNOSTIC ===\n\n";

// Check latest token
$latestToken = DB::table('personal_access_tokens')
    ->orderBy('created_at', 'desc')
    ->first();

if ($latestToken) {
    echo "Latest Token Details:\n";
    echo "  ID: {$latestToken->id}\n";
    echo "  User ID: {$latestToken->tokenable_id}\n";
    echo "  Name: {$latestToken->name}\n";
    echo "  Token (first 20 chars): " . substr($latestToken->token, 0, 20) . "...\n";
    echo "  Token Length: " . strlen($latestToken->token) . " chars\n";
    echo "  Created: {$latestToken->created_at}\n";
    echo "  Last Used: " . ($latestToken->last_used_at ?? 'NEVER') . "\n";
    echo "  Abilities: {$latestToken->abilities}\n\n";
    
    // Get the user
    $user = DB::table('users')->where('id', $latestToken->tokenable_id)->first();
    if ($user) {
        echo "Associated User:\n";
        echo "  Email: {$user->email}\n";
        echo "  Name: {$user->name}\n\n";
    }
    
    // Check Sanctum config
    echo "Sanctum Configuration:\n";
    $config = config('sanctum');
    echo "  Guard: " . json_encode($config['guard']) . "\n";
    echo "  Expiration: " . ($config['expiration'] ?? 'null (never expires)') . "\n";
    echo "  Prefix: " . ($config['token_prefix'] ?? 'none') . "\n\n";
    
    // Test token format
    echo "Expected Authorization Header Format:\n";
    echo "  Authorization: Bearer {$latestToken->id}|[plaintext_token_here]\n\n";
    
    echo "IMPORTANT NOTES:\n";
    echo "1. The token in database is HASHED (SHA-256)\n";
    echo "2. Thunder Client needs the PLAIN TOKEN from login/register response\n";
    echo "3. Format must be: {token_id}|{random_string}\n";
    echo "4. Example: 10|lpqW2DYzaOTuxOUPXUiHhrtyfkPq74KbcWcQ7tKzaa567dbb\n\n";
    
    // Check if middleware is configured
    echo "Middleware Check:\n";
    $middleware = app('router')->getMiddleware();
    if (isset($middleware['auth:sanctum'])) {
        echo "  ✓ auth:sanctum middleware is registered\n";
    } else {
        echo "  ✗ auth:sanctum middleware NOT found\n";
    }
    
} else {
    echo "❌ No tokens found in database!\n";
}
