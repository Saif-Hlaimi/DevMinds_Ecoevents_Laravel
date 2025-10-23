<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceLocalhost
{
    /**
     * Redirect requests hitting 127.0.0.1 to localhost to ensure a single origin in dev.
     * This avoids session / OAuth state issues and enables SDKs that require localhost.
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local')) {
            $host = $request->getHost();
            if ($host === '127.0.0.1') {
                $scheme = $request->getScheme();
                $port = $request->getPort();
                $uri = $request->getRequestUri();

                $portPart = '';
                if (($scheme === 'http' && $port && $port != 80) || ($scheme === 'https' && $port && $port != 443)) {
                    $portPart = ':' . $port;
                }

                $target = $scheme . '://localhost' . $portPart . $uri;
                return redirect()->to($target, 302);
            }
        }

        return $next($request);
    }
}
