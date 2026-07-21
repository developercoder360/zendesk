<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$companySlug = 'iota-digital-solutions';
$centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
$domain = $companySlug . '.' . $centralDomain;
$token = Illuminate\Support\Str::random(64);

$scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: request()->getScheme();
$port = parse_url(config('app.url'), PHP_URL_PORT);
$portSuffix = $port ? ':' . $port : '';
$url = $scheme . '://' . $domain . $portSuffix . '/tenant-login?token=' . $token;

echo "Built URL: " . $url . "\n";
