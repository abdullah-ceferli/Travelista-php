<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeploymentSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedHosts = config('travelista.security.allowed_hosts', []);
        abort_unless($this->hostAllowed($request->getHost(), $allowedHosts), 400, 'Invalid host.');

        if (config('travelista.security.force_https') && ! $request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        if (! $request->isMethodSafe() && $request->hasHeader('Origin')) {
            $origin = $request->header('Origin');
            $trusted = config('travelista.security.csrf_trusted_origins', []);
            abort_unless($origin === $request->getSchemeAndHttpHost() || in_array($origin, $trusted, true), 419);
        }

        $response = $next($request);
        $seconds = (int) config('travelista.security.hsts_seconds', 0);

        if ($request->isSecure() && $seconds > 0) {
            $header = 'max-age='.$seconds;
            if (config('travelista.security.hsts_include_subdomains')) {
                $header .= '; includeSubDomains';
            }
            if (config('travelista.security.hsts_preload')) {
                $header .= '; preload';
            }
            $response->headers->set('Strict-Transport-Security', $header);
        }

        return $response;
    }

    private function hostAllowed(string $host, array $allowedHosts): bool
    {
        foreach ($allowedHosts as $allowed) {
            if ($allowed === '*' || strcasecmp($host, $allowed) === 0) {
                return true;
            }
            if (str_starts_with($allowed, '.')) {
                $suffix = strtolower($allowed);
                if (strtolower($host) === substr($suffix, 1) || str_ends_with(strtolower($host), $suffix)) {
                    return true;
                }
            }
        }

        return false;
    }
}
