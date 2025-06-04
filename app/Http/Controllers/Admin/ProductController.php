<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
      public function index()
    {
        try {           
            $user_id = session('user_id');
            $admin = DB::table('users')->where('user_id', $user_id)->first();

            if (!$admin) {
                $admin = ['name' => 'Admin'];
            }
            
            $productsQuery = DB::table('products')
                ->leftJoin('users', 'products.seller_id', '=', 'users.user_id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
                ->select('products.*', 'users.name as seller_name', 'categories.name as category_name');
                
            $request = request();
            
            if ($request->has('search') && !empty($request->search)) {
                $search = '%' . $request->search . '%';
                $productsQuery->where(function($q) use ($search) {
                    $q->where('products.name', 'LIKE', $search)
                      ->orWhere('products.description', 'LIKE', $search);
                });
            }
         
            if ($request->has('category') && !empty($request->category)) {
                $productsQuery->where('products.category_id', $request->category);
            }
            
            if ($request->has('condition') && !empty($request->condition)) {
                $productsQuery->where('products.condition_type', $request->condition);
            }
            
            if ($request->has('stock_status') && !empty($request->stock_status)) {
                if ($request->stock_status == 'in_stock') {
                    $productsQuery->where('products.stock', '>', 0);
                } elseif ($request->stock_status == 'low_stock') {
                    $productsQuery->where('products.stock', '<=', 5)->where('products.stock', '>', 0);
                } elseif ($request->stock_status == 'out_of_stock') {
                    $productsQuery->where('products.stock', '<=', 0);
                }
            }
            
            if ($request->has('status') && !empty($request->status)) {
                $productsQuery->where('products.status', $request->status);
            }
            
            if ($request->has('min_price') && is_numeric($request->min_price)) {
                $productsQuery->where('products.price', '>=', (float)$request->min_price);
            }
            
            if ($request->has('max_price') && is_numeric($request->max_price)) {
                $productsQuery->where('products.price', '<=', (float)$request->max_price);
            }
              if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'oldest':
                        $productsQuery->orderBy('products.created_at', 'asc');
                        break;
                    case 'price_high':
                        $productsQuery->orderBy('products.price', 'desc');
                        break;
                    case 'price_low':
                        $productsQuery->orderBy('products.price', 'asc');
                        break;
                    case 'name_asc':
                        $productsQuery->orderBy('products.name', 'asc');
                        break;
                    case 'name_desc':
                        $productsQuery->orderBy('products.name', 'desc');
                        break;
                    case 'condition_asc':
                        $productsQuery->orderBy('products.condition_type', 'asc')
                            ->orderBy('products.created_at', 'desc');
                        break;
                    case 'condition_desc':
                        $productsQuery->orderBy('products.condition_type', 'desc')
                            ->orderBy('products.created_at', 'desc');
                        break;
                    default:
                        $productsQuery->orderBy('products.created_at', 'desc');
                }
            } else {
                $productsQuery->orderBy('products.created_at', 'desc');
            }
            $products = $productsQuery->paginate(10)->appends(request()->query());
            
            $pagination = [
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ];
            $categories = DB::table('categories')->get();
            
            $page_title = "Products Management";
            
            return response()->view('admin.products.index', [
                'page_title' => $page_title,
                'admin' => $admin,
                'products' => $products,
                'categories' => $categories,
                'pagination' => $pagination
            ]);        } catch (\Exception $e) {
            return response()->view('admin.products.index', [
                'page_title' => 'Products Management',
                'admin' => ['name' => 'Admin'],
                'error_message' => 'An error occurred while loading the products.'
            ]);
        }
    }
   
    public function bulkActions(Request $request)
    {
        try {
            $action = $request->bulk_action;
            $productIds = $request->product_ids;
            
            if (empty($action) || empty($productIds)) {
                return Redirect::back()->with('error', 'No action or products selected.');
            }
            
            switch ($action) {
                case 'activate':
                    DB::table('products')->whereIn('product_id', $productIds)->update(['status' => 'active']);
                    $message = count($productIds) . ' products activated successfully.';
                    break;
                    
                case 'deactivate':
                    DB::table('products')->whereIn('product_id', $productIds)->update(['status' => 'inactive']);
                    $message = count($productIds) . ' products deactivated successfully.';
                    break;
                    
                case 'delete':
                    DB::table('products')->whereIn('product_id', $productIds)->delete();
                    $message = count($productIds) . ' products deleted successfully.';
                    break;
                    
                default:
                    $message = 'Unknown action: ' . $action;
                    break;
            }
              return Redirect::back()->with('success', $message);
            
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred during bulk operation.');
        }
    }
    
    public function deleteProduct($id)
    {
        try {
            $product = DB::table('products')->where('product_id', $id)->first();
            
            if (!$product) {
                return Redirect::back()->with('error', 'Product not found.');
            }
            
            DB::table('products')->where('product_id', $id)->delete();
              return Redirect::back()->with('success', 'Product deleted successfully.');
            
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred while deleting the product.');
        }
    }
        public function viewProduct($id)
    {
        try {
            $user_id = session('user_id');
            $admin = DB::table('users')->where('user_id', $user_id)->first();

            if (!$admin) {
                $admin = ['name' => 'Admin'];
            }
            
            $product = DB::table('products')
                ->leftJoin('users', 'products.seller_id', '=', 'users.user_id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
                ->select('products.*', 'users.name as seller_name', 'categories.name as category_name')
                ->where('products.product_id', $id)
                ->first();
            
            if (!$product) {
                return Redirect::route('admin.products')->with('error', 'Product not found.');
            }
            
            $product_images = DB::table('product_images')
                ->where('product_id', $id)
                ->get();
            
            return response()->view('admin.products.view', [
                'page_title' => 'View Product - ' . $product->name,
                'admin' => $admin,
                'product' => $product,
                'product_images' => $product_images            ]);
            
        } catch (\Exception $e) {
            return Redirect::route('admin.products')->with('error', 'An error occurred while viewing the product.');
        }
    }
    
    public function editProduct($id)
    {
        try {
            $user_id = session('user_id');
            $admin = DB::table('users')->where('user_id', $user_id)->first();

            if (!$admin) {
                $admin = ['name' => 'Admin'];
            }
            
            $product = DB::table('products')
                ->where('product_id', $id)
                ->first();
            
            if (!$product) {
                return Redirect::route('admin.products')->with('error', 'Product not found.');
            }
            
            $product_images = DB::table('product_images')
                ->where('product_id', $id)
                ->get();
            
            $categories = DB::table('categories')->get();
            
            return response()->view('admin.products.edit', [
                'page_title' => 'Edit Product - ' . $product->name,
                'admin' => $admin,
                'product' => $product,
                'product_images' => $product_images,
                'categories' => $categories            ]);
            
        } catch (\Exception $e) {
            return Redirect::route('admin.products')->with('error', 'An error occurred while editing the product.');
        }
    }
       
    public function updateProduct(Request $request, $id)
    {
        try {
        
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,category_id',
                'status' => 'required|in:active,inactive',
                'condition_type' => 'required|in:New,Like New,Good,Fair,Poor',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            ]);
            
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            
            $product = DB::table('products')->where('product_id', $id)->first();
            
            if (!$product) {
                return Redirect::back()->with('error', 'Product not found.');
            }
            
            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'condition_type' => $request->condition_type,
                'status' => $request->status,
                'updated_at' => now()
            ];
            
            if ($request->has('description') && !is_null($request->description)) {
                $data['description'] = $request->description;
            }
            
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $data['image'] = $imageName;
            } elseif ($request->hasFile('image')) {
                $image = $request->file('image');
                
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($image->getMimeType(), $validTypes)) {
                    return Redirect::back()->with('error', 'Invalid image type. Only JPG, JPEG or PNG files are allowed.');
                }
                
                $maxSize = 2 * 1024 * 1024;
                if ($image->getSize() > $maxSize) {
                    return Redirect::back()->with('error', 'Image file is too large. Maximum size is 2MB.');
                }
                
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $data['image'] = $imageName;
            }
            
            DB::table('products')->where('product_id', $id)->update($data);
            
            if ($request->hasFile('additional_images')) {
                foreach($request->file('additional_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $imageName);
                    
                    DB::table('product_images')->insert([
                        'product_id' => $id,
                        'image_path' => $imageName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            if ($request->has('delete_images')) {
                DB::table('product_images')->whereIn('image_id', $request->delete_images)->delete();        }
              return Redirect::back()->with('success', 'Product updated successfully.');
            
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred while updating the product.');
        }
    }

    public function create()
    {
        try {
            $categories = DB::table('categories')->orderBy('name')->get();
            
            $user_id = session('user_id');
            $admin = DB::table('users')->where('user_id', $user_id)->first();
            
            if (!$admin) {
                $admin = (object)['name' => 'Admin'];
            }
              return view('admin.products.create', compact('categories', 'admin'));
            
        } catch (\Exception $e) {
            return Redirect::to('/admin/products')->with('error', 'Unable to load create product page.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,category_id',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'condition_type' => 'required|in:New,Like New,Good,Fair,Poor',
                'status' => 'required|in:active,inactive',
                'seller_id' => 'nullable|exists:users,user_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
            }

            $productData = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'condition_type' => $request->condition_type,
                'status' => $request->status,
                'seller_id' => $request->seller_id ?: null,
                'created_at' => now(),
                'updated_at' => now()
            ];            if ($imageName) {
                $productData['image'] = $imageName;
            }
            
            $productId = DB::table('products')->insertGetId($productData);
            return Redirect::to('/admin/products')->with('success', 'Product created successfully.');
            
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', 'An error occurred while creating the product: ' . $e->getMessage());
        }
    }
}
