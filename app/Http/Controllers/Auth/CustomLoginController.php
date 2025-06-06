<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomLoginController extends Controller
{
   
    public function showLoginForm()
    {
       
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }          if (!function_exists('my_url')) {
            function my_url($path = null) {
                $baseUrl = config('app.url', 'http://localhost');
                $path = $path ? '/' . ltrim($path, '/') : '';
                return $baseUrl . $path;
            }
        }
        
        if (!function_exists('asset')) {
            function asset($path) {
                return my_url('/' . ltrim($path, '/'));
            }
        }
        
        if (!function_exists('csrf_field')) {
            function csrf_field() {
                $token = csrf_token();
                return '<input type="hidden" name="_token" value="' . $token . '">';
            }
        }
        
        if (!function_exists('old')) {
            function old($key, $default = '') {
                return session()->getOldInput($key, $default);
            }
        }
        
        $errors = session('errors') ? session('errors')->toArray() : [];
        
        ob_start();
        include resource_path('views/auth/login.blade.php');
        $content = ob_get_clean();
        
        return response($content);
    }
}
