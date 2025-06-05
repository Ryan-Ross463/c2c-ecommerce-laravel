<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$originalUri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$skippedFiles = [
];

if (in_array($originalUri, $skippedFiles)) {
    return false; 
}

$isProduction = isset($_SERVER['RAILWAY_PUBLIC_DOMAIN']);
define('APP_BASE_PATH', $isProduction ? '' : '/c2c_ecommerce/C2C_ecommerce_laravel');

$basePath = APP_BASE_PATH;
$processedUri = $originalUri;
if (!empty($basePath) && strpos($processedUri, $basePath) === 0) {
    $processedUri = substr($processedUri, strlen($basePath));
   
    $processedUri = ltrim($processedUri, '/');
}

$public = __DIR__ . '/public/';

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

$cleanUri = $processedUri === '' ? '/' : '/' . $processedUri;

$_SERVER['REQUEST_URI'] = $cleanUri;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PATH_INFO'] = $cleanUri;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);