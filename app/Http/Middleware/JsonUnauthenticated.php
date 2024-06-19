<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

class JsonUnauthenticated
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            return $next($request);
        } catch (AuthenticationException $e) {
            if($request->expectsJson()){
                return response(json_encode(['message' => 'Unauthenticated.']), 401)->header('Content-Type', 'application/json');
            } else {
                // Add appropriate return statement here
                // For example, you might redirect to a login page:
                return redirect('login');
            }
        }
    }
}
