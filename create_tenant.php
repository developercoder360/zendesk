<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenant = App\Models\Tenant::create();
$tenant->domains()->create(['domain' => 'tenant1.zendesk.test']);

echo "Tenant created: " . $tenant->id . "\n";
