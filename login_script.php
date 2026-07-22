<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
tenancy()->initialize('63c67449-5dee-48e7-b625-149cbbdb9926');
$user = App\Models\User::where('email', 'info@iota-digital.com')->first();
$token = Illuminate\Support\Str::random(64);
cache()->put('tenant_login_'.$token, ['user_id' => $user->id, 'redirect' => '/dashboard', 'remember' => true], now()->addMinutes(5));
echo 'http://iota-digital-solutions.localhost:8000/tenant-login?token=' . $token . "\n";
