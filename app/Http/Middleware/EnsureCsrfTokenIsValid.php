<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCsrfTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($request->isMethod('GET') && !isset($_SESSION['_token'])) {
            $_SESSION['_token'] = md5(uniqid(mt_rand(), true));
        }
        
        if ($request->isMethod('POST') && !$this->isExempt($request)) {
            $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
            
            if (!$token || !isset($_SESSION['_token']) || $token !== $_SESSION['_token']) {

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'CSRF token mismatch'], 419);
                }
                
                return redirect()->back()->with('error', 'CSRF token mismatch. Please try again.');
            }
        }
        
        return $next($request);
    }
    
    /**
     * Determine if the request is exempt from CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */    protected function isExempt($request)
    {
        $exemptRoutes = [

        ];
        
        foreach ($exemptRoutes as $route) {
            if (strpos($request->path(), $route) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
