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

// Otherwise, load the Laravel public/index.php
require_once __DIR__.'/public/index.php';
