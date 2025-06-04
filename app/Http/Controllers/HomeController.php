<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{    
    /**
     * Display the homepage.
     *
     * @return \Illuminate\Http\Response
     */      
    public function index(Request $request)
    {
       
        $request->session()->put('homepage_visited', date('Y-m-d H:i:s'));
        
        $featured_products = DB::table('products')
            ->leftJoin('users', 'products.seller_id', '=', 'users.user_id')
            ->select('products.*', 'users.name as seller_name')
            ->where('products.status', 'active')
            ->orderBy('products.created_at', 'desc')
            ->limit(6)
            ->get();       
        $trending_products = DB::table('products')
            ->leftJoin('users', 'products.seller_id', '=', 'users.user_id')
            ->select('products.*', 'users.name as seller_name')
            ->where('products.status', 'active')
            ->orderBy('products.views', 'desc')
            ->orderBy('products.created_at', 'desc') 
            ->limit(6)
            ->get();
           
        $bestselling_products = DB::table('products')
            ->leftJoin('users', 'products.seller_id', '=', 'users.user_id')
            ->select('products.*', 'users.name as seller_name')
            ->where('products.status', 'active')
            ->orderBy('products.sales_count', 'desc')
            ->orderBy('products.created_at', 'desc') 
            ->limit(6)
            ->get();
          
        $popular_categories = DB::table('categories')
            ->select(
                'categories.category_id', 
                'categories.name as category_name', 
                'categories.description', 
                'categories.image', 
                'categories.status',
                'categories.created_at', 
                'categories.updated_at', 
                DB::raw('COUNT(CASE WHEN products.status = "active" THEN products.product_id ELSE NULL END) as product_count')
            )
            ->leftJoin('products', 'categories.category_id', '=', 'products.category_id')
            ->groupBy(
                'categories.category_id',
                'categories.name',
                'categories.description',
                'categories.image',
                'categories.status',
                'categories.created_at',
                'categories.updated_at'
            )
            ->orderBy('product_count', 'desc')
            ->limit(8)
            ->get();
            
        $data = [
            'featured_products' => $featured_products,
            'trending_products' => $trending_products,
            'bestselling_products' => $bestselling_products,
            'categories' => $popular_categories
        ];
        
        extract($data);
        
        ob_start();
        include(resource_path('views/index.php'));
        $content = ob_get_clean();
        return response($content);
    }
}
