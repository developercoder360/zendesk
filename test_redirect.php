<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$owner = App\Models\User::first();
$token = 'test-token';
cache()->put('tenant_login_'.$token, ['user_id' => $owner->id, 'redirect' => '/dashboard'], now()->addMinutes(5));
echo "Token cached for user {$owner->id}\n";
