<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $errors = [];
        $page_title = "Login - C2C eCommerce";
        
        if (session('error')) {
            $_SESSION['error'] = session('error');
        }
        
        if (old('email')) {
            $_SESSION['_old_input']['email'] = old('email');
        }

        ob_start();
        include resource_path('views/auth/login.php');
        $content = ob_get_clean();
        
        return response($content);
    }

    public function login(Request $request)
    {
        $email = trim($request->email ?? '');
        $password = $request->password ?? '';
        
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please include an "@" in the email address.';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        }
        
        if (!empty($errors)) {
            session(['errors' => $errors]);
            session()->flashInput(['email' => $email]);
            
            $_SESSION['errors'] = $errors;
            $_SESSION['_old_input']['email'] = $email;
            
            $viewData = [
                'page_title' => "Login - C2C eCommerce",
                'custom_css' => '<link href="' . my_url('/assets/css/forms.css') . '" rel="stylesheet">',
                'errors' => $errors,
                'email' => $email
            ];
            
            ob_start();
            include resource_path('views/auth/login.php');
            $content = ob_get_clean();
            return response($content);
        }
        
        $user = \DB::table('users')->where('email', $email)->first();
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            
            session(['user_id' => Auth::user()->user_id]);
            session(['role_id' => Auth::user()->role_id]);
            
            if (Auth::user()->role_id == 3) {
                return redirect('/admin');
            } else {
                return redirect('/');
            }
        }
        
        session()->flash('error', 'Invalid email or password.');
        session()->flashInput(['email' => $email]);
        
        $viewData = [
            'page_title' => "Login - C2C eCommerce",
            'custom_css' => '<link href="' . my_url('/assets/css/forms.css') . '" rel="stylesheet">',
            'errors' => $errors,
            'email' => $email
        ];
        
        ob_start();
        include resource_path('views/auth/login.php');
        $content = ob_get_clean();
        return response($content);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
