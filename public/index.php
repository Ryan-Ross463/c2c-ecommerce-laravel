<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Get the kernel instance
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request from the server variables
$request = Illuminate\Http\Request::capture();

// Handle the request and send the response
$response = $kernel->handle($request);
$response->send();

// Terminate the application
$kernel->terminate($request, $response);
