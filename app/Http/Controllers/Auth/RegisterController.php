<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $page_title = "Register - C2C eCommerce";
        $custom_css = '<link href="' . my_url('/assets/css/register.css') . '" rel="stylesheet">';
        
        $data = [
            'page_title' => $page_title,
            'custom_css' => $custom_css,
            'formFields' => [],
            'errors' => []
        ];
        
        extract($data);
        ob_start();
        include(resource_path('views/users/register.php'));
        $content = ob_get_clean();
        return response($content);
    }

    public function register(Request $request)
    {
       
        $first_name = trim($request->first_name ?? '');
        $last_name = trim($request->last_name ?? '');
        $email = trim($request->email ?? '');
        $phone = trim($request->phone ?? '');
        $account_type = $request->account_type ?? '';
        $dob = $request->dob ?? '';
        $gender = $request->gender ?? '';
        $business_name = trim($request->business_name ?? '');
        $city = trim($request->city ?? '');
        $password = $request->password ?? '';
        $confirm_password = $request->confirm_password ?? '';
        $terms_accepted = $request->has('terms');
        
        $formFields = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'account_type' => $account_type,
            'dob' => $dob,
            'gender' => $gender,
            'business_name' => $business_name,
            'city' => $city,
        ];
        
        $errors = [];
        
        if (empty($first_name)) $errors['first_name'] = "First name is required.";
        if (empty($last_name)) $errors['last_name'] = "Last name is required.";
        
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } else {
            if (strpos($email, '@') === false) {
                $errors['email'] = "Please include an '@' in the email address.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format. Please enter a valid email address (e.g., user@example.com).";
            }
            
            if (User::where('email', $email)->exists()) {
                $errors['email'] = "This email is already registered. Please use a different email or login.";
            }
        }

        if (empty($phone)) {
            $errors['phone'] = "Phone number is required.";
        } else {
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (!preg_match('/^\d{10}$/', $phone)) {
                $errors['phone'] = "Phone number must be 10 digits.";
            }
        }

        if (empty($account_type)) $errors['account_type'] = "Please select an account type.";
        
        if (empty($dob)) {
            $errors['dob'] = "Date of birth is required.";
        } else {
            if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $dob)) {
                $dob = str_replace('/', '-', $dob); 
            }
            
            if (strtotime($dob) > strtotime('-18 years')) {
                $errors['dob'] = "You must be at least 18 years old to register.";
            }
        }
        
        if (empty($gender)) $errors['gender'] = "Please select your gender.";
        if (empty($city)) $errors['city'] = "City is required.";
        
        if (empty($password)) {
            $errors['password'] = "Password is required.";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors['password'] = "Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.";
        }
        
        if (empty($confirm_password)) {
            $errors['confirm_password'] = "Confirm password is required.";
        } elseif ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
        
        if (!$terms_accepted) $errors['terms'] = "You must accept the terms and privacy policy.";
        
        if (!empty($errors)) {
            $page_title = "Register - C2C eCommerce";
            $custom_css = '<link href="' . my_url('/assets/css/register.css') . '" rel="stylesheet">';
            
            $data = [
                'page_title' => $page_title,
                'custom_css' => $custom_css,
                'formFields' => $formFields,
                'errors' => $errors
            ];
            
            extract($data);
            ob_start();
            include(resource_path('views/users/register.php'));
            $content = ob_get_clean();
            return response($content);
        }
        
        $role_id = ($account_type === 'Seller') ? 2 : 1;
        
        $roleExists = \App\Models\Role::where('role_id', $role_id)->exists();
        
        if (!$roleExists) {
           
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            \App\Models\Role::create([
                'role_id' => 1,
                'name' => 'buyer',
                'description' => 'Regular user who can buy products'
            ]);
            
            \App\Models\Role::create([
                'role_id' => 2,
                'name' => 'seller',
                'description' => 'User who can sell products'
            ]);
            
            \App\Models\Role::create([
                'role_id' => 3,
                'name' => 'admin',
                'description' => 'Administrator with full access'
            ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        
        $user = User::create([
            'name' => $first_name . ' ' . $last_name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'account_type' => $account_type,
            'dob' => $dob,
            'gender' => $gender,
            'business_name' => $business_name,
            'city' => $city,
            'role_id' => $role_id,
            'status' => 'active'
        ]);
        
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();
            $maxSize = 2 * 1024 * 1024; 
            
            if (!in_array($fileExtension, $allowedTypes)) {
                session()->flash('error', 'Profile picture must be a JPG, JPEG, or PNG file.');
                return redirect()->back()->withInput();
            }
            
            if ($fileSize > $maxSize) {
                session()->flash('error', 'Profile picture must be less than 2MB.');
                return redirect()->back()->withInput();
            }
            
            $uploadPath = public_path('uploads/profile_pictures');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);
            
            $user->profile_image = $filename;
            $user->save();
        }
        
        auth()->login($user);
        
        session()->flash('registration_success', true);
        session()->flash('registered_name', $first_name);
        
        return redirect('/?registration=success');
    }
    
    public function showTerms()
    {
        $page_title = "Terms and Privacy Policy";
        $custom_css = '<link href="' . my_url('/assets/css/terms.css') . '" rel="stylesheet">';
        
        $data = [
            'page_title' => $page_title,
            'custom_css' => $custom_css
        ];
        
        extract($data);
        ob_start();
        include(resource_path('views/users/terms.php'));
        $content = ob_get_clean();
        return response($content);
    }
}
