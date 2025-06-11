<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$breadcrumbs = [
    'Products' => '/seller/products',
    'Edit Product' => null
];

if (!auth()->check()) {
    header("Location: /login?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$product_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$product = DB::table('products')->where('product_id', $product_id)->first();

if (!$product || $product->seller_id != auth()->user()->user_id) {
    header("Location: /seller/products");
    exit;
}

$categories = DB::table('categories')->get();

$product_images = DB::table('product_images')
    ->where('product_id', $product_id)
    ->orderBy('sort_order')
    ->pluck('image')
    ->toArray();

include(resource_path('views/layouts/seller_dashboard_header.php'));
?>

<div class="page-header">
    <h1><i class="fas fa-edit me-2"></i> Edit Product</h1>
    <div class="actions">
        <a href="/seller/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

<form id="product-form" action="/seller/products/update/<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
    <?php 
    echo csrf_field(); 
    ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo session('error'); ?>
        </div>
    <?php endif; ?>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo session('success'); ?>
        </div>
    <?php endif; ?>
    
    <div class="form-section">
        <h3>Basic Information</h3>
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product->name); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <div id="editor-container">
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($product->description); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category->category_id; ?>" <?php echo ($product->category_id == $category->category_id ? 'selected' : ''); ?>>
                                <?php echo $category->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="condition_type" class="form-label">Condition <span class="text-danger">*</span></label>
                    <select class="form-select" id="condition_type" name="condition_type" required>
                        <option value="">Select Condition</option>
                        <?php $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor']; ?>                        <?php foreach($conditions as $condition): ?>
                            <option value="<?php echo $condition; ?>" <?php echo ($product->condition_type == $condition ? 'selected' : ''); ?>>
                                <?php echo $condition; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" <?php echo ($product->status == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo ($product->status == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3>Pricing & Inventory</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="price" class="form-label">Price (R) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R</span>
                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="<?php echo $product->price; ?>" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="quantity" name="quantity" value="<?php echo $product->stock; ?>" required>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3>Product Images</h3>
        
        <?php if(!empty($product_images)): ?>
            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div class="current-images-container d-flex flex-wrap gap-3">
                    <?php foreach($product_images as $index => $image): ?>
                        <?php if(!empty($image)): ?>
                            <div class="current-image-item position-relative">
                                <img src="/uploads/products/<?php echo $image; ?>" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;" alt="Product image">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                        style="top: 5px; right: 5px; opacity: 0.8;" 
                                        onclick="toggleImageRemoval(this, '<?php echo $image; ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <input type="hidden" name="remove_images[]" value="" data-image="<?php echo $image; ?>" disabled>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="form-text text-muted mt-2">Click the trash icon to mark an image for removal when updating the product.</div>
            </div>
        <?php endif; ?>
        
        <div class="mb-3">
            <label for="product_images" class="form-label">Upload New Images <small class="text-muted">(Max 1 image, 2MB)</small></label>
            <input type="file" class="form-control product-image-upload" id="product_images" name="product_images[]" accept="image/jpeg,image/png,image/jpg" data-preview-target="#image-preview" data-multiple="false">
            <div class="form-text text-muted">Supported formats: JPEG, PNG, JPG only</div>
            <div id="image-preview" class="preview-container mt-3 d-flex flex-wrap gap-3"></div>
        </div>
    </div>
    
    <div class="form-section">
        <div class="d-flex justify-content-between">
            <button type="button" onclick="window.location.href='/seller/products'" class="btn btn-outline-secondary">
                Cancel
            </button>
            <div>
                <button type="submit" id="update-product-btn" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Product
                </button>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script src="/assets/js/edit_seller.js"></script>

<?php include(resource_path('views/layouts/seller_dashboard_footer.php')); ?>
