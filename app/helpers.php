<?php

if (!function_exists('my_url')) {
    /**
     * Generate a URL for the application without the 'public' segment.
     *
     * @param  string  $path
     * @param  mixed  $parameters
     * @param  bool  $secure
     * @return string
     */    function my_url($path = null, $parameters = [], $secure = null)
    {
        // Get the current base URL from the request
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $protocol . '://' . $host;
        
        // Clean the path and ensure it starts with /
        $path = $path ? '/' . ltrim($path, '/') : '';

        // Add query parameters if provided
        if (!empty($parameters)) {
            $path .= '?' . http_build_query($parameters);
        }
        
        return $baseUrl . $path;
    }
}

if (!function_exists('check_active')) {
    /**
     * Check if the current route matches the given path
     *
     * @param  string  $path
     * @return string
     */
    function check_active($path)
    {
        return request()->is($path) ? 'active' : '';
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        // Get the current base URL from the request
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $protocol . '://' . $host;
        
        // For Railway, don't add 'public' to the path since it's handled by the server
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            return $baseUrl . '/' . ltrim($path, '/');
        } else {
            // For local development, add 'public' to the path
            $publicPath = 'public/' . ltrim($path, '/');
            return $baseUrl . '/' . $publicPath;
        }
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = md5(uniqid(mt_rand(), true));
        }
        
        return '<input type="hidden" name="_token" value="' . $_SESSION['_token'] . '">';
    }
}

if (!function_exists('old')) {
    function old($key, $default = '') {
        return isset($_SESSION['_old_input'][$key]) ? $_SESSION['_old_input'][$key] : $default;
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null) {
        if ($key === null) {
            return $_SESSION ?? [];
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
}