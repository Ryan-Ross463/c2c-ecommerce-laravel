<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Sentry\State\Scope;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */    protected $except = [
        // CSRF verification disabled for register route
        'register',
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if ($request->isMethod('post') && app()->bound('sentry')) {
                \Sentry\configureScope(function (Scope $scope) use ($request): void {
                    $scope->setExtra('csrf_token_from_request', $request->input('_token'));
                    $scope->setExtra('csrf_token_from_session', session()->token());
                    $scope->setExtra('request_url', $request->fullUrl());
                    $scope->setExtra('request_method', $request->method());
                    $scope->setExtra('session_id', session()->getId());
                    $scope->setExtra('has_session_started', session()->isStarted());
                });
            }
            
            return parent::handle($request, $next);
        } catch (\Exception $e) {
            if (app()->bound('sentry')) {
                \Sentry\captureException($e);
            }
            throw $e;
        }
    }
}
