<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('http://ha.zendesk.127.0.0.1.nip.io/dashboard', 'GET');
$kernel->handle($request);

echo "Request Host: " . request()->getHost() . "\n";
echo "route('dashboard') => " . route('dashboard') . "\n";
$route = app('router')->getRoutes()->getByName('dashboard');
echo "Route domain: " . $route->domain() . "\n";
