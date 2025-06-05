<?php

// Simple router for PHP built-in server
// This ensures requests are properly routed to Laravel

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// If the request is for a static file in the public directory, serve it directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// For production on Railway, bypass the custom root index.php
// and go directly to Laravel's standard public/index.php
// This avoids URL duplication issues

// Set up clean server variables for Laravel
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/public/index.php';

// Ensure APP_URL is correctly set in environment
if (!empty($_ENV['RAILWAY_PUBLIC_DOMAIN']) || !empty($_SERVER['RAILWAY_PUBLIC_DOMAIN'])) {
    $domain = $_ENV['RAILWAY_PUBLIC_DOMAIN'] ?? $_SERVER['RAILWAY_PUBLIC_DOMAIN'];
    $_ENV['APP_URL'] = 'https://' . $domain;
    $_SERVER['APP_URL'] = 'https://' . $domain;
    putenv('APP_URL=https://' . $domain);
}

// Load the standard Laravel public/index.php
require_once __DIR__.'/public/index.php';
