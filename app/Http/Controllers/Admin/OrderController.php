<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
   
    public function index()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "Orders Management";
        
        return response()->view('admin.orders.index', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
}
