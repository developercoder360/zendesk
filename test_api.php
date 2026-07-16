<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$req = Illuminate\Http\Request::create("/api/v1/widget/tickets", "POST", [
    "name" => "John Doe",
    "email" => "john@example.com",
    "subject" => "Help with widget",
    "description" => "I need help setting up the widget on my site.",
    "priority" => "high"
]);
$req->headers->set("X-Tenant-Identifier", "7ed8aafc-1d8f-4843-8e65-a35330d3fb79");

$res = app()->handle($req);
echo "STATUS: " . $res->getStatusCode() . "\n";
echo "CONTENT: " . $res->getContent() . "\n";

