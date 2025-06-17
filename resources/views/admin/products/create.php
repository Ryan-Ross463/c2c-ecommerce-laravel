<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/admin_create_product.css') . '">';
$page_title = "Add New Product - Admin Dashboard";
$admin = session('admin') ?? (object)['name' => 'Admin']; 
$admin_header_path = resource_path('views/layouts/admin_header.php');
if (file_exists($admin_header_path)) {
    include($admin_header_path);
} else {
    echo "<!-- Admin header file not found at: " . $admin_header_path . " -->";
}
?>

<div class="admin-content-main">    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Add New Product</h1>
        <a href="<?php echo my_url('/admin/products'); ?>" class="btn btn-outline-secondary">
            Back to Products
        </a>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger mb-4">
            <?php echo session('error'); ?>
        </div>
    <?php endif; ?>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success mb-4">
            <?php echo session('success'); ?>
        </div>
    <?php endif; ?>

    <form id="product-form" action="<?php echo my_url('/admin/products/store'); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
          <div class="admin-card card mb-4">
            <div class="admin-header card-header">
                <h2 class="card-title mb-0">Basic Information</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo old('name') ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo old('description') ?? ''; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category->category_id; ?>" 
                                            <?php echo (old('category_id') == $category->category_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="condition_type" class="form-label">Condition <span class="text-danger">*</span></label>
                            <select class="form-select" id="condition_type" name="condition_type" required>
                                <option value="">Select Condition</option>
                                <option value="New" <?php echo (old('condition_type') == 'New') ? 'selected' : ''; ?>>New</option>
                                <option value="Like New" <?php echo (old('condition_type') == 'Like New') ? 'selected' : ''; ?>>Like New</option>
                                <option value="Good" <?php echo (old('condition_type') == 'Good') ? 'selected' : ''; ?>>Good</option>
                                <option value="Fair" <?php echo (old('condition_type') == 'Fair') ? 'selected' : ''; ?>>Fair</option>
                                <option value="Poor" <?php echo (old('condition_type') == 'Poor') ? 'selected' : ''; ?>>Poor</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?php echo (old('status') == 'active' || !old('status')) ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (old('status') == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card card mb-4">
            <div class="admin-header card-header">
                <h2 class="card-title mb-0">Pricing & Inventory</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (R) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" 
                                       value="<?php echo old('price') ?? ''; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" id="stock" name="stock" 
                                   value="<?php echo old('stock') ?? '1'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="seller_id" class="form-label">Assign to Seller <small class="text-muted">(optional)</small></label>
                            <select class="form-select" id="seller_id" name="seller_id">
                                <option value="">Admin Created</option>
                                <?php
                                $sellers = DB::table('users')
                                    ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
                                    ->select('users.*', 'roles.name as role_name')
                                    ->where('users.role_id', 2) 
                                    ->orderBy('users.name')
                                    ->get();
                                foreach($sellers as $seller): ?>
                                    <option value="<?php echo $seller->user_id; ?>" 
                                            <?php echo (old('seller_id') == $seller->user_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($seller->name . ' (' . $seller->email . ') - Seller'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card card mb-4">
            <div class="admin-header card-header">
                <h2 class="card-title mb-0">Product Image</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Product Image <small class="text-muted">(optional)</small></label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg">
                    <div class="form-text text-muted">
                        Supported formats: JPEG, PNG, JPG only. Maximum file size: 2MB.
                    </div>
                    <div id="image-preview" class="mt-3" style="display: none;">
                        <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <button type="button" onclick="window.location.href='<?php echo my_url('/admin/products'); ?>'" class="btn btn-outline-secondary">
                        Cancel
                    </button>
                    <button type="submit" id="save-product-btn" class="btn btn-primary">
                        Save Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

document.getElementById('product-form').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'description', 'category_id', 'condition_type', 'price', 'stock'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>

<?php
if (file_exists(resource_path('views/layouts/admin_footer.php'))) {
    include(resource_path('views/layouts/admin_footer.php'));
} else {
    echo "<!-- Admin footer file not found -->";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
}
?>
