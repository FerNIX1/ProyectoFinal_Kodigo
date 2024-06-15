<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false,
                'data' => null
            ], 401);
        }
        $token = explode(' ', $authHeader)[1];
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false,
                'data' => null
            ], 401);
        }

        try{
            $user = JWTAuth::parseToken()->authenticate($token);
            error_log('User: ' . $user);
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false,
                'data' => null
            ], 401);
        }
    }
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
