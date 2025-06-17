<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$breadcrumbs = [
    'Products' => '/seller/products',
    'Add New' => null
];

if (!auth()->check()) {
    header("Location: /login?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$categories = DB::table('categories')->get();

include(resource_path('views/layouts/seller_dashboard_header.php'));
?>

<div class="page-header">
    <h1><i class="fas fa-plus-circle me-2"></i> Add New Product</h1>    <div class="actions">
        <a href="/seller/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

<form id="product-form" action="/seller/products/store" method="POST" enctype="multipart/form-data">
    <?php 
    echo csrf_field(); 
    ?>    
    <script>console.log('Form action URL: /seller/products/store');</script>
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
                    <input type="text" class="form-control" id="name" name="name" value="" required>
                </div>                <div class="mb-3">
                    <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                    <div id="editor-container">
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category->category_id; ?>">
                                <?php echo $category->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>                <div class="mb-3">
                    <label for="condition_type" class="form-label">Condition <span class="text-danger">*</span></label>
                    <select class="form-select" id="condition_type" name="condition_type" required>
                        <option value="">Select Condition</option>
                        <option value="New">New</option>
                        <option value="Like New">Like New</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-section">
        <h3>Pricing & Inventory</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">                    <label for="price" class="form-label">Regular Price (R) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R</span>
                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="" required>
                    </div>
                </div>
  
            </div>
            <div class="col-md-6">                
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="stock" name="stock" value="1" required>
                </div>
            </div>
        </div>
    </div>
    <div class="form-section">        <h3>Product Images</h3>
        <div class="mb-3">            <label for="product_images" class="form-label">Upload Product Image <small class="text-muted">(Optional, Max 1 image, 2MB)</small></label>            <input type="file" class="form-control" id="product_images" name="product_images[]" accept="image/jpeg,image/png,image/jpg">
            <div class="form-text text-muted">Optional. Upload product images (Max 2MB each). Supported formats: JPEG, PNG, JPG</div>
            <div id="image-preview" class="preview-container mt-3"></div>
            <input type="hidden" name="image_names" id="image_names">
        </div>
    </div>
    <div class="form-section">        <div class="d-flex justify-content-between">            <button type="button" onclick="window.location.href='/seller/products'" class="btn btn-outline-secondary">
                Cancel
            </button>
            <button type="submit" id="save-product-btn" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Product
            </button>
        </div>    </div>
</form>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script src="/assets/js/create_seller.js"></script>

<?php include(resource_path('views/layouts/seller_dashboard_footer.php')); ?>