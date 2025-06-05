<?php

// Simple router for PHP built-in server
// This ensures requests are properly routed to Laravel

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// If the request is for a static file in the public directory, serve it directly
if ($uri !== '/') {
    $publicFile = __DIR__.'/public'.$uri;
    if (file_exists($publicFile)) {
        // Set appropriate content type for different file types
        $extension = pathinfo($publicFile, PATHINFO_EXTENSION);
        switch ($extension) {
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
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                break;
        }
        readfile($publicFile);
        return;
    }
}

// For production on Railway, bypass the custom root index.php
// and go directly to Laravel's standard public/index.php
// This avoids URL duplication issues

// Force correct server variables for Railway deployment
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/public/index.php';

// Ensure clean REQUEST_URI (remove any domain duplication)
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    // Remove any domain name that might have gotten into the path
    $domain = $_SERVER['HTTP_HOST'] ?? '';
    if ($domain && strpos($requestUri, $domain) !== false) {
        $requestUri = str_replace('/' . $domain, '', $requestUri);
        $_SERVER['REQUEST_URI'] = $requestUri;
    }
}

// Set up environment variables for Railway
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
    $domain = $_SERVER['HTTP_HOST'];
    $_ENV['APP_URL'] = 'https://' . $domain;
    $_SERVER['APP_URL'] = 'https://' . $domain;
    putenv('APP_URL=https://' . $domain);
    
    // Force HTTPS
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['REQUEST_SCHEME'] = 'https';
}

// Load the standard Laravel public/index.php
require_once __DIR__.'/public/index.php';
