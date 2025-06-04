<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Cookie\CookieJar;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class StartSession extends \Illuminate\Session\Middleware\StartSession
{
    public function handle($request, Closure $next)
    {
        $response = parent::handle($request, $next);

        if (!Session::has('_token')) {
            Session::put('_token', csrf_token());
        }

        return $response;
    }
}
