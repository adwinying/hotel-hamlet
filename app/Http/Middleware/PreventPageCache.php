<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventPageCache
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->header('Cache-Control', 'no-cache, no-store');

        return $response;
    }
}
