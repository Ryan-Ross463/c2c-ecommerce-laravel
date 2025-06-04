<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/admin_products.css') . '">';
$page_title = "Products Management - Admin Dashboard";
$admin = session('admin') ?? (object)['name' => 'Admin']; 
$admin_header_path = resource_path('views/layouts/admin_header.php');
if (file_exists($admin_header_path)) {
    include($admin_header_path);
} else {
    echo "<!-- Admin header file not found at: " . $admin_header_path . " -->";
}
?>

<div class="admin-content-main">
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Products Management</h1>
        <a href="<?php echo my_url('/admin/products/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <div class="admin-card card mb-4">
        <div class="admin-header card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">Product Filters</h2>
            <button class="btn btn-sm btn-outline-secondary toggle-filters">
                <i class="fas fa-filter me-2"></i>Toggle Filters
            </button>
        </div>
        
        <div class="card-body filters-container">
            <form action="<?php echo my_url('/admin/products'); ?>" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search:</label>
                    <input type="text" id="search" name="search" class="form-control" 
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>

                <div class="col-md-3">
                    <label for="category" class="form-label">Category:</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="1" <?php echo isset($_GET['category']) && $_GET['category'] == '1' ? 'selected' : ''; ?>>Electronics</option>
                        <option value="2" <?php echo isset($_GET['category']) && $_GET['category'] == '2' ? 'selected' : ''; ?>>Clothing</option>
                        <option value="3" <?php echo isset($_GET['category']) && $_GET['category'] == '3' ? 'selected' : ''; ?>>Home & Garden</option>
                        <option value="4" <?php echo isset($_GET['category']) && $_GET['category'] == '4' ? 'selected' : ''; ?>>Books</option>
                        <option value="5" <?php echo isset($_GET['category']) && $_GET['category'] == '5' ? 'selected' : ''; ?>>Toys & Games</option>
                        <option value="6" <?php echo isset($_GET['category']) && $_GET['category'] == '6' ? 'selected' : ''; ?>>Sports</option>
                        <option value="7" <?php echo isset($_GET['category']) && $_GET['category'] == '7' ? 'selected' : ''; ?>>Beauty</option>
                        <option value="8" <?php echo isset($_GET['category']) && $_GET['category'] == '8' ? 'selected' : ''; ?>>Automotive</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="condition" class="form-label">Condition:</label>
                    <select id="condition" name="condition" class="form-select">
                        <option value="">All Conditions</option>
                        <option value="New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'New' ? 'selected' : ''; ?>>New</option>
                        <option value="Like New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Like New' ? 'selected' : ''; ?>>Like New</option>
                        <option value="Good" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Good' ? 'selected' : ''; ?>>Good</option>
                        <option value="Fair" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Fair' ? 'selected' : ''; ?>>Fair</option>
                        <option value="Poor" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Poor' ? 'selected' : ''; ?>>Poor</option>
                    </select>
                </div>                  <!-- Stock status filter removed as requested -->
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status:</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo isset($_GET['status']) && $_GET['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo isset($_GET['status']) && $_GET['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By:</label>
                    <select id="sort" name="sort" class="form-select">                        <option value="newest" <?php echo !isset($_GET['sort']) || $_GET['sort'] == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="price_high" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_high' ? 'selected' : ''; ?>>Price (High to Low)</option>
                        <option value="price_low" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_low' ? 'selected' : ''; ?>>Price (Low to High)</option>
                        <option value="name_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                        <option value="name_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                        <option value="condition_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'condition_asc' ? 'selected' : ''; ?>>Condition (Best to Worst)</option>
                        <option value="condition_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'condition_desc' ? 'selected' : ''; ?>>Condition (Worst to Best)</option>
                    </select>
                </div>                <div class="col-md-12 mt-3">
                    <?php 
                    $min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
                    $max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000;
                    $max_slider = max(10000, $max_price); 
                    ?>
                    <label for="price_range" class="form-label">Price Range: <span class="price-display">R<span id="currentMinPrice"><?php echo $min_price; ?></span> - R<span id="currentMaxPrice"><?php echo $max_price; ?></span></span></label>
                    <div class="price-range-container">
                        <div class="range-slider-wrapper">
                            <input type="range" class="form-range range-slider" id="price_range" 
                                min="0" max="<?php echo $max_slider; ?>" step="100" 
                                value="<?php echo $max_price; ?>">
                            <div class="price-range-labels d-flex justify-content-between mt-1">
                                <small>R0</small>
                                <small>R<?php echo floor($max_slider/2); ?></small>
                                <small>R<?php echo $max_slider; ?>+</small>
                            </div>
                        </div>
                        <div class="price-inputs mt-2">
                            <div class="input-group">
                                <span class="input-group-text">R</span>
                                <input type="number" class="form-control" id="min_price" name="min_price" 
                                    placeholder="Min" min="0" value="<?php echo $min_price; ?>">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">R</span>
                                <input type="number" class="form-control" id="max_price" name="max_price" 
                                    placeholder="Max" min="0" value="<?php echo $max_price; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                        <a href="<?php echo my_url('/admin/products'); ?>" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>    <div class="admin-card card">
        <div class="admin-header card-header">
            <h2 class="card-title mb-0">Product Listings</h2>
        </div>
        
        <div class="bulk-actions-container d-none">
            <form id="bulkActionsForm" action="<?php echo my_url('/admin/products/bulk-actions'); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <strong>With Selected (<span id="selectedCount">0</span>):</strong>
                    </div>
                    <select name="bulk_action" class="form-select form-select-sm w-auto me-2">
                        <option value="">Choose Action</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary me-2" id="applyBulkAction" disabled>Apply</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelBulkAction">Cancel</button>
                </div>
            </form>
        </div>
        
        <div class="table-responsive product-table">
            <table class="table table-striped table-hover">                <thead>
                    <tr>
                        <th class="checkbox-column">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll"></label>
                            </div>
                        </th>
                        <th data-column="product-id">ID</th>
                        <th data-column="product-image">Image</th>                        <th data-column="product-name">Name</th>
                        <th data-column="product-category">Category</th>
                        <th data-column="product-condition">Condition</th>
                        <th data-column="product-price">Price</th>
                        <th data-column="product-stock">Stock</th>
                        <th data-column="product-seller">Seller</th>
                        <th data-column="product-status">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>                </thead>
                <tbody>
                    <?php if(isset($products) && $products->count() > 0): ?>
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input product-select" type="checkbox" name="product_ids[]" value="<?php echo $product->product_id; ?>" form="bulkActionsForm">
                                        <label class="form-check-label"></label>
                                    </div>                                </td>
                                <td><?php echo $product->product_id; ?></td>                                <td>
                                    <div class="product-image-container">
                                        <?php if(!empty($product->image)): ?>
                                            <img src="<?php echo asset('uploads/products/' . $product->image); ?>" 
                                                alt="<?php echo htmlspecialchars($product->name); ?>" 
                                                class="product-thumbnail">
                                        <?php else: ?>
                                            <img src="<?php echo asset('uploads/products/default.jpg'); ?>" 
                                                alt="No image available" 
                                                class="product-thumbnail">
                                        <?php endif; ?>
                                    </div>                                </td><td><?php echo htmlspecialchars($product->name); ?></td>
                                <td><?php echo htmlspecialchars($product->category_name); ?></td>
                                <td><?php echo isset($product->condition_type) ? htmlspecialchars($product->condition_type) : 'N/A'; ?></td>
                                <td>R<?php echo number_format($product->price, 2); ?></td>
                                <td>
                                    <?php
                                    $stockClass = 'stock-high';
                                    $stockIcon = 'check-circle';
                                    
                                    if ($product->stock <= 0) {
                                        $stockClass = 'stock-low';
                                        $stockIcon = 'times-circle';
                                    } elseif ($product->stock <= 5) {
                                        $stockClass = 'stock-medium';
                                        $stockIcon = 'exclamation-circle';
                                    }
                                    ?>
                                    <div class="stock-indicator <?php echo $stockClass; ?>">
                                        <i class="fas fa-<?php echo $stockIcon; ?>"></i>
                                        <span><?php echo $product->stock; ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($product->seller_name); ?></td>
                                <td>
                                    <span class="badge <?php echo $product->status === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($product->status); ?>
                                    </span>
                                </td>                                <td>
                                    <div class="d-flex justify-content-end gap-2">                                        <button type="button" class="btn btn-sm btn-primary quick-edit-btn" 
                                            data-id="<?php echo $product->product_id; ?>"
                                            data-name="<?php echo htmlspecialchars($product->name); ?>"
                                            data-price="<?php echo $product->price; ?>"
                                            data-stock="<?php echo $product->stock; ?>"
                                            data-status="<?php echo $product->status; ?>"
                                            data-condition="<?php echo $product->condition_type; ?>"
                                            data-category="<?php echo $product->category_id; ?>"
                                            data-description="<?php echo htmlspecialchars($product->description ?? ''); ?>"
                                            title="Quick Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-product" 
                                            data-id="<?php echo $product->product_id; ?>"
                                            data-name="<?php echo htmlspecialchars($product->name); ?>"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="no-data-message">
                                    <i class="fas fa-box-open mb-3 fa-3x"></i>
                                    <h4>No products found</h4>
                                    <p>There are no products matching your criteria.</p>
                                    <?php if(isset($_GET['search']) || isset($_GET['category']) || isset($_GET['condition']) || isset($_GET['status'])): ?>
                                        <a href="<?php echo my_url('/admin/products'); ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-sync-alt me-2"></i>Clear Filters
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo my_url('/admin/products/create'); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i>Add First Product
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="card-footer">
            <nav aria-label="Product pagination">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?php echo $pagination['current_page'] == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo my_url('/admin/products?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item <?php echo $pagination['current_page'] == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo my_url('/admin/products?' . http_build_query(array_merge($_GET, ['page' => max(1, $pagination['current_page'] - 1)]))); ?>">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>
                    
                    <?php 
                    $start_page = max(1, $pagination['current_page'] - 2);
                    $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
                    
                    for($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                        <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo my_url('/admin/products?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $pagination['current_page'] == $pagination['total_pages'] ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo my_url('/admin/products?' . http_build_query(array_merge($_GET, ['page' => min($pagination['total_pages'], $pagination['current_page'] + 1)]))); ?>">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item <?php echo $pagination['current_page'] == $pagination['total_pages'] ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo my_url('/admin/products?' . http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']]))); ?>">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>    </div>
</div>

<!-- Quick Edit Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-labelledby="quickEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickEditModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <div class="modal-body">
                <form id="quickEditForm" action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <select class="form-select" id="edit_category" name="category_id">
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category->category_id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_price" class="form-label">Price (R)</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control" id="edit_price" name="price" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_condition" class="form-label">Condition</label>
                                <select class="form-select" id="edit_condition" name="condition_type">
                                    <option value="New">New</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">                            <div class="mb-3">
                                <label for="edit_image" class="form-label">Upload New Image</label>
                                <input type="file" class="form-control" id="edit_image" name="image" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Only JPG, JPEG or PNG files less than 2MB are allowed</small>
                            </div>
                        </div>
                    </div>
                      <div class="alert alert-info small mt-3">
                        <i class="fas fa-info-circle me-2"></i> This quick edit form allows you to update basic product information. You can upload a new image without changing any other fields. For advanced editing including multiple images, use the full edit page.
                    </div><div class="mb-3">
                        <label for="edit_description" class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4" placeholder="Leave empty to keep current description"></textarea>
                    </div>
                </form>
            </div>            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveQuickEdit">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteProductName"></strong>?
                <p class="text-danger mt-2 mb-0">This action cannot be undone.</p>
            </div>            <div class="modal-footer">
                <form id="deleteProductForm" action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

<script src="<?php echo asset('assets/js/admin_products.js'); ?>"></script>
