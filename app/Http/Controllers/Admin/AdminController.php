<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{    public function index()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();

        if (!$admin) {
            $admin = ['name' => 'Admin'];
        }
        
        $user_count = DB::table('users')->count();
        
        $product_count = Schema::hasTable('products') ? DB::table('products')->count() : 0;
        
        $order_count = Schema::hasTable('orders') ? DB::table('orders')->count() : 0;
          $page_title = "Admin Dashboard";
        
        return response()->view('admin.dashboard', [
            'page_title' => $page_title,
            'admin' => $admin,
            'user_count' => $user_count,
            'product_count' => $product_count,
            'order_count' => $order_count
        ]);
    }
}
