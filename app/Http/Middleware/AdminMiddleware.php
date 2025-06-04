<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Role ID 3 is admin
            if (auth()->user()->role_id == 3) {
                return $next($request);
            }
        }
        
        if (session()->has('user_id') && session('role_id') == 3) {
            return $next($request);
        }
        
        return redirect('/login')->with('error', 'You must be an admin to access this area');
    }
}
