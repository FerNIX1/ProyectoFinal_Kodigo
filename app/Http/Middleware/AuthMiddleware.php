<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

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
            return $this->unauthenticatedResponse();
        }
        $token = explode(' ', $authHeader)[1] ?? null;
        if (!$token) {
            return $this->unauthenticatedResponse();
        }

        try {
            $user = JWTAuth::parseToken()->authenticate($token);
            Log::info('User: ' . $user);
            return $next($request);
        } catch (JWTException $e) {
            Log::error('JWT Exception: ' . $e->getMessage());
            return $this->unauthenticatedResponse();
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response(json_encode([
                'message' => 'Server error',
                'status' => false,
                'data' => null
            ]), 500)->header('Content-Type', 'application/json');
        }
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticatedResponse()
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}