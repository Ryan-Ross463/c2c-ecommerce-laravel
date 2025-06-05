<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Redirect;

Route::middleware(['web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/terms', [RegisterController::class, 'showTerms'])->name('terms');
    
    Route::get('/about', function () {
        return response()->view('pages.about', ['page_title' => 'About Us']);
    })->name('about');
    
    Route::get('/contact', function () {
        return response()->view('pages.contact', ['page_title' => 'Contact Us']);
    })->name('contact');
});

Route::middleware(['web'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    
    Route::middleware(['auth'])->prefix('seller')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Seller\ProfileController::class, 'index'])->name('seller.profile');
        Route::post('/profile/update', [\App\Http\Controllers\Seller\ProfileController::class, 'update'])->name('seller.profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\Seller\ProfileController::class, 'updatePassword'])->name('seller.profile.password');
        
        Route::get('/products', [\App\Http\Controllers\Seller\ProductController::class, 'index'])->name('seller.products');
        Route::get('/products/create', [\App\Http\Controllers\Seller\ProductController::class, 'create'])->name('seller.products.create');
        Route::post('/products/store', [\App\Http\Controllers\Seller\ProductController::class, 'store'])->name('seller.products.store');
        Route::get('/products/edit/{id}', [\App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('seller.products.edit');
        Route::post('/products/update/{id}', [\App\Http\Controllers\Seller\ProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/products/delete/{id}', [\App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('seller.products.delete');
        Route::post('/products/bulk-action', [\App\Http\Controllers\Seller\ProductController::class, 'bulkAction'])->name('seller.products.bulk');
        
        Route::post('/products/image/upload', [\App\Http\Controllers\Seller\ProductController::class, 'uploadImage'])->name('seller.products.upload.image');
        Route::post('/products/image/delete', [\App\Http\Controllers\Seller\ProductController::class, 'deleteImage'])->name('seller.products.delete.image');
        
        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('seller.orders');
    });
    
    Route::middleware(['auth'])->prefix('buyer')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Buyer\ProfileController::class, 'index'])->name('buyer.profile');
        Route::post('/profile/update', [\App\Http\Controllers\Buyer\ProfileController::class, 'update'])->name('buyer.profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\Buyer\ProfileController::class, 'updatePassword'])->name('buyer.profile.password');
    });
});

Route::prefix('admin')->middleware(['web', 'admin'])->group(function () {
    
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('admin.profile.password');
    
    Route::get('/users/manage_users', [\App\Http\Controllers\Admin\UserController::class, 'manageUsers'])->name('admin.users.manage');
    Route::get('/users/create_admin', [\App\Http\Controllers\Admin\UserController::class, 'createAdmin'])->name('admin.users.create');
    Route::post('/users/create_admin', [\App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('admin.users.store');
    Route::get('/users/view/{id}', [\App\Http\Controllers\Admin\UserController::class, 'viewUser'])->name('admin.users.view');
    Route::get('/users/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/users/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [\App\Http\Controllers\Admin\UserController::class, 'deleteUser'])->name('admin.users.delete');
    
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products/store', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/view/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'viewProduct'])->name('admin.products.view');
    Route::get('/products/edit/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/update/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/products/bulk-actions', [\App\Http\Controllers\Admin\ProductController::class, 'bulkActions'])->name('admin.products.bulk-actions');
    Route::post('/products/delete/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteProduct'])->name('admin.products.delete');
    
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
    
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/orders', [\App\Http\Controllers\Admin\ReportController::class, 'orders'])->name('admin.reports.orders');
    Route::get('/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('/reports/user_activity', [\App\Http\Controllers\Admin\ReportController::class, 'userActivity'])->name('admin.reports.activity');
});

// Temporary route to create admin user on Railway - REMOVE AFTER USE
Route::get('/create-admin-user-once', function () {
    try {
        // Check if admin role exists, create if not
        $adminRole = DB::table('roles')->where('role_id', 3)->first();
        if (!$adminRole) {
            DB::table('roles')->insert([
                'role_id' => 3,
                'name' => 'admin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Check if admin user already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@c2cecommerce.com')->first();
        
        if (!$existingAdmin) {
            DB::table('users')->insert([
                'name' => 'System Administrator',
                'email' => 'admin@c2cecommerce.com',
                'password' => Hash::make('Admin123!@#'),
                'role_id' => 3,
                'account_type' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response('<h1>✅ SUCCESS!</h1><p>Admin user created successfully!</p><p><strong>Email:</strong> admin@c2cecommerce.com</p><p><strong>Password:</strong> Admin123!@#</p><p><a href="/admin/login">Go to Admin Login</a></p><p style="color:red;"><strong>IMPORTANT:</strong> Remove this route from web.php after use!</p>');
        } else {
            return response('<h1>ℹ️ Admin Already Exists</h1><p>Admin user already exists in database.</p><p><strong>Email:</strong> admin@c2cecommerce.com</p><p><strong>Password:</strong> Admin123!@#</p><p><a href="/admin/login">Go to Admin Login</a></p>');
        }
    } catch (Exception $e) {
        return response('<h1>❌ Error</h1><p>Error creating admin user: ' . $e->getMessage() . '</p>');
    }
});

Route::fallback(function () {
    return response("Route not found. URL: " . request()->url() . 
                   "<br>Path: " . request()->path() . 
                   "<br>APP_URL: " . config('app.url'), 404);
});
