<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking database...\n\n";

try {
    $tokenCount = DB::table('personal_access_tokens')->count();
    $userCount = DB::table('users')->count();
    
    echo "Total users: $userCount\n";
    echo "Total tokens: $tokenCount\n\n";
    
    if ($tokenCount > 0) {
        echo "Recent tokens:\n";
        $tokens = DB::table('personal_access_tokens')
            ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
            ->select('users.email', 'personal_access_tokens.name', 'personal_access_tokens.created_at', 'personal_access_tokens.last_used_at')
            ->orderBy('personal_access_tokens.created_at', 'desc')
            ->limit(5)
            ->get();
        
        foreach ($tokens as $token) {
            echo "  - User: {$token->email}, Token: {$token->name}, Created: {$token->created_at}, Last Used: {$token->last_used_at}\n";
        }
    } else {
        echo "❌ NO TOKENS FOUND!\n";
        echo "Tokens are NOT being saved to the database.\n";
    }
    
    echo "\nUsers in database:\n";
    $users = DB::table('users')->select('id', 'name', 'email', 'created_at')->get();
    foreach ($users as $user) {
        echo "  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
