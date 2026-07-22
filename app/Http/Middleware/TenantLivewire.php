<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class TenantLivewire
{
    public function handle(Request $request, Closure $next)
    {
        $centralDomains = config('tenancy.central_domains', []);
        
        if (!in_array($request->getHost(), $centralDomains)) {
            return app(InitializeTenancyByDomain::class)->handle($request, $next);
        }
        
        return $next($request);
    }
}
