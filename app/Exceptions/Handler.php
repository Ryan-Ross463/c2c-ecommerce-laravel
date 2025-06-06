<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Sentry\State\Scope;
use Throwable;

class Handler extends ExceptionHandler
{
   
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                \Sentry\captureException($e);
            }
        });
        
        $this->reportable(function (TokenMismatchException $e) {
            if (app()->bound('sentry')) {
                \Sentry\configureScope(function (Scope $scope): void {
                    $scope->setExtra('session_id', session()->getId() ?? 'not available');
                    $scope->setExtra('session_started', session()->isStarted() ? 'yes' : 'no');
                    $scope->setExtra('request_url', request()->fullUrl());
                    $scope->setExtra('request_method', request()->method());
                    $scope->setExtra('user_agent', request()->userAgent() ?? 'not available');
                    $scope->setExtra('csrf_token_in_session', session()->has('_token') ? 'yes' : 'no');
                    $scope->setExtra('csrf_token_value', session()->get('_token', 'not found'));
                    $scope->setExtra('request_has_token', request()->has('_token') ? 'yes' : 'no');
                    $scope->setExtra('request_token_value', request()->input('_token', 'not found'));
                    
                    $scope->setFingerprint(['CSRF-419', request()->path()]);
                    
                    $scope->setTag('error_type', 'csrf_validation');
                });
                
                \Sentry\captureException($e);
            }
        });
        
        $this->renderable(function (QueryException $e, $request) {
            if (str_contains($e->getMessage(), 'sessions') && $request->expectsJson()) {
                return response()->json([
                    'error' => 'Session database error',
                    'message' => 'There was an error with the session database. Please try again later.'
                ], 500);
            }
            
            if (str_contains($e->getMessage(), 'sessions')) {
              
                Log::error('Sessions table error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                config(['session.driver' => 'file']);
                
                return response()->view('errors.session', [
                    'message' => 'There was an error with the session database. Please try again later.'
                ], 500);
            }
        });
        
        $this->renderable(function (TokenMismatchException $e, $request) {
           
            Log::warning('CSRF token mismatch', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'has_session' => session()->isStarted() ? 'yes' : 'no',
                'session_id' => session()->getId() ?? 'none'
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'CSRF token mismatch',
                    'message' => 'Your session has expired. Please refresh the page and try again.'
                ], 419);
            }
            
            return redirect()->back()->withInput($request->except('_token'))->with([
                'error' => 'Your session has expired. Please try again.'
            ]);
        });
    }
}
