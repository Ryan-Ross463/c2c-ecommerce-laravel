<?php 
$custom_css = '<link rel="stylesheet" href="' . asset('assets/css/admin_products.css') . '">
<link rel="stylesheet" href="' . asset('assets/css/admin_product_details.css') . '">';
$page_title = "View Product - Admin Dashboard";
$admin = session('admin') ?? (object)['name' => 'Admin']; 
$admin_header_path = resource_path('views/layouts/admin_header.php');
if (file_exists($admin_header_path)) {
    include($admin_header_path);
} else {
    echo "<!-- Admin header file not found at: " . $admin_header_path . " -->";
}
?>

<div class="admin-content-main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo my_url('/admin/dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo my_url('/admin/products'); ?>">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Product</li>
                </ol>
            </nav>
            <h1 class="mt-2 mb-0">Product Details</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo my_url('/admin/products/edit/' . $product->product_id); ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <button type="button" class="btn btn-danger delete-product" 
                data-id="<?php echo $product->product_id; ?>"
                data-name="<?php echo htmlspecialchars($product->name); ?>">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo session('success'); ?>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo session('error'); ?>
        </div>
    <?php endif; ?>    <div class="row">
        <div class="col-md-5">
            <div class="admin-card card mb-4">
                <div class="admin-header card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">Product Images</h2>
                    <span class="badge <?php echo $product->status === 'active' ? 'bg-success' : 'bg-danger'; ?> fs-6">
                        <?php echo ucfirst($product->status); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="product-main-image mb-3">
                        <?php if(!empty($product->image)): ?>
                            <img src="<?php echo asset('uploads/products/' . $product->image); ?>" 
                                alt="<?php echo htmlspecialchars($product->name); ?>" 
                                class="img-fluid rounded">
                        <?php else: ?>
                            <img src="<?php echo asset('uploads/products/default.jpg'); ?>" 
                                alt="No image available" 
                                class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                    <?php if(!empty($product_images) && count($product_images) > 0): ?>
                        <div class="product-gallery">
                            <h6 class="fw-bold mb-2">Additional Images</h6>
                            <div class="row g-2">
                                <?php foreach($product_images as $image): ?>
                                    <div class="col-4">
                                        <img src="<?php echo asset('uploads/products/' . $image->image_path); ?>" 
                                            alt="Product image" 
                                            class="img-thumbnail">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="admin-card card mb-4">
                <div class="admin-header card-header">
                    <h2 class="card-title mb-0">Basic Information</h2>
                </div>
                <div class="card-body">
                    <div class="product-info-grid">
                        <div class="info-item">
                            <span class="info-label">Product ID:</span>
                            <span class="info-value"><?php echo $product->product_id; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($product->name); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Price:</span>
                            <span class="info-value fw-bold">R<?php echo number_format($product->price, 2); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category:</span>
                            <span class="info-value"><?php echo htmlspecialchars($product->category_name); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Condition:</span>
                            <span class="info-value"><?php echo htmlspecialchars($product->condition_type); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Stock:</span>
                            <span class="info-value">
                                <?php
                                $stockClass = 'text-success';
                                if ($product->stock <= 0) {
                                    $stockClass = 'text-danger';
                                } elseif ($product->stock <= 5) {
                                    $stockClass = 'text-warning';
                                }
                                ?>
                                <span class="<?php echo $stockClass; ?> fw-bold"><?php echo $product->stock; ?></span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="badge <?php echo $product->status === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo ucfirst($product->status); ?>
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Seller:</span>
                            <span class="info-value">
                                <a href="<?php echo my_url('/admin/users/view/' . $product->seller_id); ?>">
                                    <?php echo htmlspecialchars($product->seller_name); ?>
                                </a>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date Added:</span>
                            <span class="info-value"><?php echo date('M d, Y', strtotime($product->created_at)); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated:</span>
                            <span class="info-value"><?php echo date('M d, Y', strtotime($product->updated_at)); ?></span>
                        </div>
                    </div>                </div>
            </div>

            <div class="admin-card card mb-4">
                <div class="admin-header card-header">
                    <h2 class="card-title mb-0">Product Statistics</h2>
                </div>
                <div class="card-body">
                    <div class="row stats-container">
                        <div class="col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">0</span>
                                    <span class="stat-label">Orders</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">0</span>
                                    <span class="stat-label">Views</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">0</span>
                                    <span class="stat-label">Wishlist</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">0</span>
                                    <span class="stat-label">Reviews</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card card mb-4">
                <div class="admin-header card-header">
                    <h2 class="card-title mb-0">Product Description</h2>
                </div>
                <div class="card-body">
                    <div class="product-description">
                        <?php if(!empty($product->description)): ?>
                            <?php echo nl2br(htmlspecialchars($product->description)); ?>
                        <?php else: ?>
                            <p class="text-muted">No description available for this product.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteProductName"></strong>?
                <p class="text-danger mt-2 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
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
