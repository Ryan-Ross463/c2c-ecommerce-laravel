<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller 
{    public function __construct()
    {
       
    }    public function index(Request $request)
    {        $query = DB::table('products')
            ->join('users', 'products.seller_id', '=', 'users.user_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('product_images', function($join) {
                $join->on('products.product_id', '=', 'product_images.product_id')
                     ->where('product_images.is_main', '=', 1);
            })
            ->select('products.*', 'users.name as seller_name', 'categories.name as category_name', 'product_images.image as main_image')
            ->where('products.status', 'active');
            
            if ($request->has('category') && $request->category) {
                $query->where('products.category_id', $request->category);
            }            
              if ($request->has('search') && $request->search) {
                $search = '%' . $request->search . '%';
                $query->where(function($q) use ($search) {
                    $q->where('products.name', 'like', $search)
                      ->orWhere('products.description', 'like', $search)
                      ->orWhere('categories.name', 'like', $search);
                });
            }
              if ($request->has('min_price') && $request->min_price !== null && $request->min_price !== '') {
                $query->where('products.price', '>=', (float)$request->min_price);
            }
            
            if ($request->has('max_price') && $request->max_price !== null && $request->max_price !== '') {
                $query->where('products.price', '<=', (float)$request->max_price);
            }
            
            if ($request->has('condition') && $request->condition) {
                $query->where('products.condition_type', $request->condition);
            }
            
            if ($request->has('in_stock') && $request->in_stock == 1) {
                $query->where('products.stock', '>', 0);
            }
            
            $sort = $request->sort ?? 'newest';
            switch ($sort) {
                case 'popular':
                    $query->orderBy('products.views', 'desc');
                    break;
                case 'bestselling':
                    $query->orderBy('products.sales_count', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('products.price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('products.price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('products.created_at', 'desc');
                    break;
            }
            
            $per_page = 12;
            $page = $request->input('page', 1); 
            $total_products = $query->count();
            $total_pages = ceil($total_products / $per_page);
            $showing_from = (($page - 1) * $per_page) + 1;
            $showing_to = min($showing_from + $per_page - 1, $total_products);
            
            $products = $query->skip(($page - 1) * $per_page)
                              ->take($per_page)
                              ->get();
               $categories = \DB::table('categories')
                ->select(
                    'categories.category_id',
                    'categories.name as category_name',
                    'categories.image',
                    \DB::raw('COUNT(CASE WHEN products.status = "active" THEN products.product_id ELSE NULL END) as product_count')
                )
                ->leftJoin('products', 'categories.category_id', '=', 'products.category_id')
                ->groupBy(
                    'categories.category_id',
                    'categories.name',
                    'categories.image'
                )
                ->get();
                  $products_count = $query->count();
            
            $data = [
                'products' => $products,
                'categories' => $categories,
                'current_category' => $request->category,
                'current_sort' => $sort,
                'search' => $request->search,
                'products_count' => $products_count,
                'total_products' => $total_products,
                'total_pages' => $total_pages,
                'current_page' => $page,
                'showing_from' => $showing_from,
                'showing_to' => $showing_to,
                'per_page' => $per_page
            ];
            
            extract($data);
            
            ob_start();
            include(resource_path('views/products/browse.php'));
            $content = ob_get_clean();
            return response($content);
    }
      public function show($id)
    {          $product = DB::table('products')
            ->join('users', 'products.seller_id', '=', 'users.user_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('product_images', function($join) {
                $join->on('products.product_id', '=', 'product_images.product_id')
                     ->where('product_images.is_main', '=', 1);
            })
            ->select('products.*', 'users.name as seller_name', 'users.email as seller_email', 'users.created_at as seller_joined', 'categories.name as category_name', 'product_images.image as main_image')
            ->where('products.product_id', $id)
            ->first();
        
        if (!$product) {
            return redirect()->route('home')->with('error', 'Product not found.');
        }
        
        DB::table('products')
            ->where('product_id', $id)
            ->increment('views');          $related_products = DB::table('products')
            ->join('users', 'products.seller_id', '=', 'users.user_id')
            ->leftJoin('product_images', function($join) {
                $join->on('products.product_id', '=', 'product_images.product_id')
                     ->where('product_images.is_main', '=', 1);
            })
            ->select('products.*', 'users.name as seller_name', 'product_images.image as main_image')
            ->where('products.category_id', $product->category_id)
            ->where('products.product_id', '!=', $id)
            ->where('products.status', 'active')
            ->orderBy('products.views', 'desc')
            ->limit(4)
            ->get();
        
        $data = [
            'product' => $product,
            'related_products' => $related_products
        ];
        
        extract($data);
        
        ob_start();
        include(resource_path('views/products/show.php'));
        $content = ob_get_clean();
        return response($content);
    }
}
