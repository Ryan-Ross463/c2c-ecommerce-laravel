<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{    
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Product::with(['category', 'images'])
            ->where('seller_id', $user->user_id); 
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        if ($request->has('stock')) {
            if ($request->stock == 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock == 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<', 10);
            } elseif ($request->stock == 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'sales':
                    $query->orderBy('sales_count', 'desc');
                    break;
                case 'views':
                    $query->orderBy('views', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = 12;
        $products = $query->paginate($perPage);
        
        $categories = Category::all();
        
        $pagination = [
            'current_page' => $products->currentPage(),
            'total_pages' => $products->lastPage(),
            'per_page' => $perPage,
            'total' => $products->total()
        ];

        return view('seller.products.index', compact('products', 'categories', 'pagination'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }    public function store(Request $request)
    {
        // Debug: Log that the store method is being called
        \Log::info('ProductController store method called', [
            'request_data' => $request->all(),
            'user_id' => Auth::user()->user_id ?? 'not_authenticated'
        ]);
          $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'condition_type' => 'required|string|in:New,Like New,Good,Fair,Poor',
            'status' => 'required|string|in:active,inactive',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Made optional
        ]);if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Image upload is now optional
        $uploadPath = public_path('uploads/products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $imageNames = [];
        if ($request->hasFile('product_images')) {
            $images = $request->file('product_images');
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                $image->move($uploadPath, $imageName);
                $imageNames[] = $imageName;
            }
        }try {
            
            $product = new Product();
            $product->seller_id = Auth::user()->user_id; 
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->category_id = $request->category_id;
            $product->condition_type = $request->condition_type;
            $product->status = $request->status;

            $product->save();

            if (!empty($imageNames)) {
                $mainImage = $imageNames[0];
                $product->image = $mainImage;
                $product->save();

                foreach ($imageNames as $index => $imageName) {
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->product_id;
                    $productImage->image = $imageName;
                    $productImage->sort_order = $index;
                    $productImage->is_main = ($index === 0) ? 1 : 0;
                    $productImage->save();
                }            }

            return redirect()->route('seller.products')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while saving the product: ' . $e->getMessage())
                ->withInput();
        }
    }    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('image')
            ]);
        }

        try {
            $image = $request->file('image');
            $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            $path = public_path('uploads/products');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $image->move($path, $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => asset('uploads/products/' . $filename)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ]);
        }
    }    public function deleteImage(Request $request)
    {
        $filename = $request->input('filename');
        
        if (!$filename) {
            return response()->json([
                'success' => false,
                'message' => 'No filename provided'
            ]);
        }

        try {
            $path = public_path('uploads/products/' . $filename);
            
            if (file_exists($path)) {
                unlink($path);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ]);
        }
    }
  public function edit($id)
    {
        $product = Product::with(['images', 'category'])->findOrFail($id);
        
        $user = Auth::user();
        if ($product->seller_id !== $user->user_id) { 
            return redirect()->route('seller.products.index')
                ->with('error', 'You do not have permission to edit this product.');
        }

        $categories = Category::all();
        
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
       
        $product = Product::with('images')->findOrFail($id);
        
        $user = Auth::user();
        if ($product->seller_id !== $user->user_id) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You do not have permission to edit this product.');
        }        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'condition_type' => 'required|string|in:New,Like New,Good,Fair,Poor',
            'status' => 'required|string|in:active,inactive',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }        try {
           
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->quantity;
            $product->category_id = $request->category_id;
            $product->condition_type = $request->condition_type;
            $product->status = $request->status;
            
            $product->save();            if ($request->has('remove_images') && is_array($request->remove_images)) {
                foreach ($request->remove_images as $filename) {
                  
                    if (empty($filename) || trim($filename) === '') {
                        continue;
                    }
                    
                    $filename = basename($filename);
                    
                    $path = public_path('uploads/products/' . $filename);
                    
                    if (file_exists($path) && is_file($path)) {
                        unlink($path);
                    }
                    
                    ProductImage::where('product_id', $product->product_id)
                                ->where('image', $filename)
                                ->delete();
                }
            }            if ($request->hasFile('product_images')) {
                $uploadedImages = [];
                
                foreach ($request->file('product_images') as $image) {
                    $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    
                    $path = public_path('uploads/products');
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                  
                    $image->move($path, $filename);

                    $uploadedImages[] = $filename;
                    
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->product_id;
                    $productImage->image = $filename;
                    $productImage->sort_order = 0;
                    $productImage->is_main = 0;
                    $productImage->save();
                }
                  if (!empty($uploadedImages)) {
                    $hasMainImage = ProductImage::where('product_id', $product->product_id)
                                                ->where('is_main', 1)
                                                ->exists();
                    
                    if (!$hasMainImage && !empty($uploadedImages)) {
                        ProductImage::where('product_id', $product->product_id)
                                    ->where('image', $uploadedImages[0])
                                    ->update(['is_main' => 1]);
                    }
                }
            }

            return redirect()->route('seller.products.edit', ['id' => $product->product_id])
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating the product: ' . $e->getMessage())
                ->withInput();
        }
    }

      public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $user = Auth::user();
        if ($product->seller_id !== $user->user_id) { 
            return redirect()->route('seller.products.index')
                ->with('error', 'You do not have permission to delete this product.');
        }        try {
            foreach ($product->images as $image) {
                $path = public_path('uploads/products/' . $image->image);
                
                if (file_exists($path)) {
                    unlink($path);
                }
                
                $image->delete();
            }
           
            $product->delete();
            
            return redirect()->route('seller.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('seller.products.index')
                ->with('error', 'An error occurred while deleting the product: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = explode(',', $request->input('product_ids', ''));
        
        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No products selected.');
        }      
        $user = Auth::user();
        $products = Product::whereIn('product_id', $productIds)
            ->where('seller_id', $user->user_id) 
            ->get();
        
        if ($products->count() === 0) {
            return redirect()->back()->with('error', 'No valid products selected.');
        }
        
        try {
            switch ($action) {
                case 'activate':
                    foreach ($products as $product) {
                        $product->status = 'active';
                        $product->save();
                    }
                    $message = 'Products activated successfully.';
                    break;
                    
                case 'deactivate':
                    foreach ($products as $product) {
                        $product->status = 'inactive';
                        $product->save();
                    }
                    $message = 'Products deactivated successfully.';
                    break;
                      case 'delete':
                    foreach ($products as $product) {
                        // Delete product images from filesystem
                        foreach ($product->images as $image) {
                            $path = public_path('uploads/products/' . $image->image);
                            
                            if (file_exists($path)) {
                                unlink($path);
                            }
                            
                            $image->delete();
                        }
                        
                        $product->delete();
                    }
                    $message = 'Products deleted successfully.';
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Invalid action specified.');
            }
            
            return redirect()->route('seller.products.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
