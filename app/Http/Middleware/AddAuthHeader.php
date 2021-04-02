<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class AddAuthHeader
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) {
            if ($request->hasCookie(config('passport.cookie.name'))) {
                $token = $request->cookie(config('passport.cookie.name'));
                $request->headers->add(['Authorization' => 'Bearer ' . $token]);
            }
        }
        return $next($request);
    }
}
