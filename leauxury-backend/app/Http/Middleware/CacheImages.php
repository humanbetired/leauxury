<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheImages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (str_starts_with($request->path(), 'storage/')) {
            $response->header('Cache-Control', 'public, max-age=31536000, immutable');
        }

        return $response;
    }
}