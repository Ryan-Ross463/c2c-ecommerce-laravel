<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{    public function showLinkRequestForm()
    {
     
        $errors = [];
        $email = '';
        $message = '';
        $message_type = '';
        
        ob_start();
        include resource_path('views/users/forgot_password.php');
        $content = ob_get_clean();
        
        return response($content);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $email = trim($request->email ?? '');
        $errors = [];
        $message = '';
        $message_type = '';
        
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        
        if (empty($errors)) {
           
            $user = User::where('email', $email)->first();
            
            if ($user) {
              
                $reset_token = Str::random(60);
                $reset_token_expiry = now()->addHour();
                
                DB::table('password_resets')->updateOrInsert(
                    ['email' => $email],
                    [
                        'token' => $reset_token,
                        'created_at' => now()
                    ]
                );
                
                $message = "A password reset link has been sent to your email. Please check your inbox.";
                $message_type = "success";
                
            } else {
                $message = "No account found with that email address.";
                $message_type = "danger";            }
        }
        
        session()->flash('message', $message);
        session()->flash('message_type', $message_type);
        
        if (!empty($errors)) {
            session()->flash('errors', $errors);
        }
        
        if (!empty($email)) {
            session()->flashInput(['email' => $email]);
        }
        
        ob_start();
        include resource_path('views/users/forgot_password.php');
        $content = ob_get_clean();
        
        return response($content);
    }
}
