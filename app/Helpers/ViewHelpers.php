<?php
if (!function_exists('get_session_var')) {
    /**
     * Get a session variable with a default value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_session_var($key, $default = null) {
        return session($key, $default);
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    function is_logged_in() {
        return session()->has('user_id');
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if logged in user is admin
     *
     * @return bool
     */
    function is_admin() {
        return session()->has('role_id') && session('role_id') == 3;
    }
}

if (!function_exists('user_name')) {
    /**
     * Get logged in user's name
     *
     * @return string|null
     */
    function user_name() {
        return session('user_name');
    }
}

if (!function_exists('flash_message')) {
    /**
     * Set a flash message in the session
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    function flash_message($message, $type = 'success') {
        session()->flash('message', $message);
        session()->flash('message_type', $type);
    }
}

if (!function_exists('has_flash_message')) {
    /**
     * Check if there is a flash message
     *
     * @return bool
     */
    function has_flash_message() {
        return session()->has('message');
    }
}

if (!function_exists('get_flash_message')) {
    /**
     * Get the flash message
     *
     * @return string|null
     */
    function get_flash_message() {
        return session('message');
    }
}

if (!function_exists('get_flash_message_type')) {
    /**
     * Get the flash message type
     *
     * @return string
     */    function get_flash_message_type() {
        return session('message_type', 'success');
    }
}

if (!function_exists('hasError')) {
    /**
     * Check if there is an error for a given field
     *
     * @param mixed $errors Error bag from Laravel
     * @param string $field Field name to check
     * @return bool
     */
    function hasError($errors, $field) {
        return isset($errors) && is_object($errors) && $errors->has($field);
    }
}

if (!function_exists('getError')) {
    /**
     * Get the error message for a given field
     *
     * @param mixed $errors Error bag from Laravel
     * @param string $field Field name to get error for
     * @return string
     */
    function getError($errors, $field) {
        return isset($errors) && is_object($errors) && $errors->has($field) ? $errors->first($field) : '';
    }
}
