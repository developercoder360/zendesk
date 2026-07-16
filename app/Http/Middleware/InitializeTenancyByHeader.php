<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class InitializeTenancyByHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header("X-Tenant-Identifier");
        
        if (!$tenantId) {
            return response()->json(["error" => "Missing X-Tenant-Identifier header"], 400);
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json(["error" => "Tenant not found"], 404);
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
