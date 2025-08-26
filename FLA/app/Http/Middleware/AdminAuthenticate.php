<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AdminAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function unauthenticated($request, array $guards)
    {
        // JSON response for API requests
        if ($request->expectsJson()) {
            return (response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be logged in as admin to access this resource'
            ], 401));
        }

        // Default behavior for non-API
        abort(401, 'Unauthorized');
    }
    protected function redirectTo($request)
    {
        // For API calls, return JSON instead of redirect
        if ($request->expectsJson()) {
            abort(response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be logged in as admin to access this resource'
            ], 401));
        }

        // fallback for web requests
        return route('login');
    }
}
