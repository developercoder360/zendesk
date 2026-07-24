<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WidgetSetting;
use Symfony\Component\HttpFoundation\Response;

class WidgetCspHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $embedKey = $request->query('key');
        $setting = null;
        if ($embedKey) {
            $setting = WidgetSetting::where('embed_key', $embedKey)->first();
        } elseif (tenant('id')) {
            $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        }

        $allowedDomains = ($setting && is_array($setting->allowed_domains)) ? $setting->allowed_domains : [];

        if (empty($allowedDomains)) {
            $csp = "frame-ancestors 'none';";
        } else {
            $sources = ["'self'"];
            foreach ($allowedDomains as $d) {
                $clean = trim($d);
                if (!empty($clean)) {
                    if (!preg_match('#^https?://#i', $clean)) {
                        $sources[] = 'http://' . $clean;
                        $sources[] = 'https://' . $clean;
                    } else {
                        $sources[] = $clean;
                    }
                }
            }
            $csp = "frame-ancestors " . implode(' ', array_unique($sources)) . ";";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
