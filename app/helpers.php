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
        // For Railway deployment, ensure proper HTTPS URL
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            // Ensure we have a protocol
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
        }
        
        $path = $path ? '/' . ltrim($path, '/') : '';

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
        // For Railway deployment, ensure proper HTTPS URL
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            // Ensure we have a protocol
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
        }
        return $baseUrl . '/' . ltrim($path, '/');
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