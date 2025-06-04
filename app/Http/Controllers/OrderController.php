<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->user_id;
        return view('seller.orders.index', [
            'page_title' => 'Orders - Seller Dashboard'
        ]);
    }
}
