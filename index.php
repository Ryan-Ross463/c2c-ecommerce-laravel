<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Get the original request URI and process it
$originalUri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$skippedFiles = [
];

if (in_array($originalUri, $skippedFiles)) {
    return false; 
}

define('APP_BASE_PATH', '/c2c_ecommerce/C2C_ecommerce_laravel');

$basePath = APP_BASE_PATH;
$processedUri = $originalUri;
if (strpos($processedUri, $basePath) === 0) {
    $processedUri = substr($processedUri, strlen($basePath));
    // Clean up the URI - remove leading slash if present since we'll add it back
    $processedUri = ltrim($processedUri, '/');
}

$public = __DIR__ . '/public/';

// Check if this is a static file request first
if ($processedUri !== '' && file_exists($public . $processedUri)) {
    $ext = pathinfo($processedUri, PATHINFO_EXTENSION);
    switch ($ext) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
    }
    readfile($public . $processedUri);
    exit;
}

// Set up the correct URI for Laravel before it captures the request
$cleanUri = $processedUri === '' ? '/' : '/' . $processedUri;

// Modify server variables BEFORE Laravel captures the request
$_SERVER['REQUEST_URI'] = $cleanUri;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PATH_INFO'] = $cleanUri;

// Set Laravel start time
define('LARAVEL_START', microtime(true));

// Check for maintenance mode
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// Get the kernel instance
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request from the MODIFIED server variables
$request = Request::capture();

// Handle the request and send the response
$response = $kernel->handle($request);
$response->send();

// Terminate the application
$kernel->terminate($request, $response);