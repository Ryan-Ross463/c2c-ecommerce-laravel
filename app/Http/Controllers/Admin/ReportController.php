<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "Reports Dashboard";
        
        return response()->view('admin.reports.index', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
    
    public function sales()
    {
       
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "Sales Reports";
        
        return response()->view('admin.reports.sales', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
      public function userActivity()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "User Activity Report";
        
        return response()->view('admin.reports.user_activity', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
    
    public function orders()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "Orders Report";
        
        return response()->view('admin.reports.orders', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
}
