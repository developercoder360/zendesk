<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::create('/livewire/update', 'POST');
$request->headers->set('HOST', 'localhost:8000'); // Central domain

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Tenant Identified? " . (tenant('id') ? 'YES (' . tenant('id') . ')' : 'NO') . "\n";
