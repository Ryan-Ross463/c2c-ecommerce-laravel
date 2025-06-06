<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Intervention\Image\Facades\Image;

class SellerController extends Controller
{    
    public function products()
    {
        $user = Auth::user();
        $user_id = $user->user_id;  
        
        $products = Product::where('seller_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $categories = Category::all();
        
        return view('seller.products.index', [
            'products' => $products,
            'categories' => $categories,
            'page_title' => 'Manage Products - Seller Dashboard'
        ]);
    }
    
    public function createProduct()
    {
        $categories = Category::all();
        
        return view('seller.products.create', [
            'categories' => $categories,
            'page_title' => 'Add New Product - Seller Dashboard'
        ]);
    }        public function storeProduct(Request $request)
    {        
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'condition' => 'required|in:New,Like New,Good,Fair,Poor',
            'quantity' => 'required|integer|min:0',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
          try {
            $product = new Product();            $product->name = $request->name;            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
              $validConditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];
            if (in_array($request->condition, $validConditions)) {
                $product->condition_type = $request->condition;
            } else {
                $product->condition_type = 'Good';
            }
              $product->stock = $request->quantity;
            $product->seller_id = Auth::user()->user_id; 
            $product->status = 'active';
            $product->save();
            
            if ($request->hasFile('product_images')) {                try {
                    $uploadPath = public_path('uploads/products');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                        chmod($uploadPath, 0777); 
                    }
                    
                    $images = [];
                    foreach ($request->file('product_images') as $image) {
                        if ($image->isValid()) {
                            $extension = $image->getClientOriginalExtension();
                            $filename = time() . '_' . uniqid() . '.' . $extension;
                            
                            try {
                                $image->move($uploadPath, $filename);
                                if (file_exists($uploadPath . '/' . $filename)) {
                                    $images[] = $filename;
                                }
                            } catch (\Exception $e) {
                               
                            }
                        }
                    }
                    
                    if (!empty($images)) {
                        $product->image = json_encode($images);
                        $product->save();
                    }
                } catch (\Exception $e) {
                  
                }
            }
              return redirect()->route('seller.products')
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }
      
    public function editProduct($id)
    {
        $user = Auth::user();
        $user_id = $user->user_id;
        $product = Product::findOrFail($id);
        
        if ($product->seller_id != $user_id) {
            return redirect()->route('seller.products')
                ->with('error', 'You do not have permission to edit this product.');
        }
        
        $categories = Category::all();
        
        return view('seller.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'page_title' => 'Edit Product - Seller Dashboard'
        ]);
    }
      
    public function updateProduct(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = $user->user_id; 
        $product = Product::findOrFail($id);
         
        if ($product->seller_id != $user_id) {
            return redirect()->route('seller.products')
                ->with('error', 'You do not have permission to update this product.');
        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'condition' => 'required|in:New,Like New,Good,Fair,Poor',
            'quantity' => 'required|integer|min:0',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
         
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->condition_type = $request->condition;
        $product->stock = $request->quantity;
        $product->status = $request->status ?? $product->status;      
        if ($request->hasFile('product_images')) {
          
            $uploadPath = public_path('uploads/products');            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                chmod($uploadPath, 0777); 
            }
            
            $currentImages = json_decode($product->image ?? '[]', true);
           
            if (!is_array($currentImages)) {
                $currentImages = [];
            }
              foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                 
                    $extension = $image->getClientOriginalExtension();
                    
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    
                    try {
                       
                        $image->move($uploadPath, $filename);
                          if (file_exists($uploadPath . '/' . $filename)) {
                            $currentImages[] = $filename;
                        }                    } catch (\Exception $e) {
                       
                    }
                }
            }
            
            $product->image = json_encode($currentImages);
        }       
        if ($request->has('remove_images')) {
            $currentImages = json_decode($product->image ?? '[]', true);
            if (!is_array($currentImages)) {
                $currentImages = [];
            }
            
            $imagesToRemove = $request->input('remove_images', []);
           
            $imagesToRemove = array_filter($imagesToRemove);
            
            $updatedImages = array_filter($currentImages, function($image) use ($imagesToRemove) {
                return !in_array($image, $imagesToRemove);
            });
            
            foreach ($imagesToRemove as $image) {
                if (!empty($image)) {
                    $path = public_path('uploads/products/' . $image);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
            
            $product->image = json_encode(array_values($updatedImages));
        }
        
        $product->save();
        
        return redirect()->route('seller.products')
            ->with('success', 'Product updated successfully!');
    }   
    public function deleteProduct($id)
    {
        $user = Auth::user();
        $user_id = $user->user_id; 
        $product = Product::findOrFail($id);
        
        if ($product->seller_id != $user_id) {
            return redirect()->route('seller.products')
                ->with('error', 'You do not have permission to delete this product.');
        }
        if (!empty($product->image)) {
           
            $images = json_decode($product->image, true);
            
            if ($images === null || !is_array($images)) {
                $images = [$product->image];
            }
              foreach ($images as $image) {
                $path = public_path('uploads/products/' . $image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
        
        $product->delete();
        
        return redirect()->route('seller.products')
            ->with('success', 'Product deleted successfully!');
    }
      public function bulkAction(Request $request)
    {
        $action = $request->input('action_type');
        $product_ids_json = $request->input('product_ids', '[]');
        $product_ids = json_decode($product_ids_json, true);
        $user = Auth::user();
        $user_id = $user->user_id; 
        
        if (empty($product_ids) || !is_array($product_ids)) {
            return redirect()->back()->with('error', 'No products selected.');
        }
        
        $count = Product::where('seller_id', $user_id)
            ->whereIn('product_id', $product_ids)
            ->count();
            
        if ($count != count($product_ids)) {
            return redirect()->back()->with('error', 'You do not have permission to modify some of these products.');
        }
        
        switch ($action) {
            case 'delete':              
                foreach ($product_ids as $id) {
                    $product = Product::find($id);
                    if ($product) {
                      
                        if (!empty($product->image)) {
                          
                            $images = json_decode($product->image, true);
                            
                            if ($images === null || !is_array($images)) {
                                $images = [$product->image];
                            }
                              foreach ($images as $image) {
                                $path = public_path('uploads/products/' . $image);
                                if (file_exists($path)) {
                                    unlink($path);
                                }
                            }
                        }
                        $product->delete();
                    }
                }
                $message = count($product_ids) . ' products deleted successfully.';
                break;
                
            case 'activate':                
                Product::whereIn('product_id', $product_ids)->update(['status' => 'active']);
                $message = count($product_ids) . ' products activated successfully.';
                break;
                
            case 'deactivate':                
                Product::whereIn('product_id', $product_ids)->update(['status' => 'inactive']);
                $message = count($product_ids) . ' products deactivated successfully.';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid action specified.');
        }
        
        return redirect()->route('seller.products')->with('success', $message);
    }
}
