<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = app('router')->getRoutes()->getRoutes();
foreach ($routes as $r) {
    if ($r->getName() === 'dashboard') {
        echo "Found dashboard route. Domain: " . ($r->domain() ?: 'NULL') . "\n";
    }
}
$byName = app('router')->getRoutes()->getByName('dashboard');
echo "getByName('dashboard') domain: " . ($byName->domain() ?: 'NULL') . "\n";
