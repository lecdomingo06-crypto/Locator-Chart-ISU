<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldForceHttps()) {
            return $next($request);
        }

        if ($this->isLocalRequest($request) || $this->isAlreadySecure($request)) {
            return $next($request);
        }

        return redirect()->secure($request->getRequestUri(), 301);
    }

    private function shouldForceHttps(): bool
    {
        return app()->environment('production') || (bool) config('app.force_https');
    }

    private function isAlreadySecure(Request $request): bool
    {
        return $request->isSecure()
            || strtolower((string) $request->headers->get('x-forwarded-proto')) === 'https';
    }

    private function isLocalRequest(Request $request): bool
    {
        return in_array($request->getHost(), ['127.0.0.1', 'localhost'], true);
    }
}
