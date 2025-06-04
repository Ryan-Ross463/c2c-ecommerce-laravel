<?php
$breadcrumbs = [
    'Products' => null
];

if (!auth()->check()) {
    header("Location: " . my_url('/login?redirect=' . urlencode($_SERVER['REQUEST_URI'])));
    exit;
}

$user = auth()->user();
$seller_id = $user->user_id;
 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$categories = DB::table('categories')->get();

$query = DB::table('products')
    ->leftJoin('product_images', function($join) {
        $join->on('products.product_id', '=', 'product_images.product_id')
             ->where('product_images.is_main', '=', 1);
    })
    ->select('products.*', 'product_images.image as main_image')
    ->where('seller_id', $seller_id) 
    ->orderBy('created_at', 'desc');

if (!empty($search)) {
    $query->where('name', 'like', '%' . $search . '%');
}

if ($category_filter > 0) {
    $query->where('category_id', $category_filter);
}

if (!empty($status_filter)) {
    $query->where('status', $status_filter);
}

$total_products = $query->count();
$total_pages = ceil($total_products / $per_page);

$products = $query->limit($per_page)->offset($offset)->get();

include(resource_path('views/layouts/seller_dashboard_header.php'));
?>

<div class="page-header">
    <h1><i class="fas fa-box me-2"></i> My Products</h1>
    <div class="actions">
        <a href="<?php echo my_url('/seller/products/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add New Product
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo my_url('/seller/products'); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Products</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name...">
                    <button type="submit" class="btn btn-primary" id="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="0">All Categories</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->category_id; ?>" <?php echo $category_filter == $category->category_id ? 'selected' : ''; ?>>
                            <?php echo $category->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo $status_filter == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status_filter == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <a href="<?php echo my_url('/seller/products'); ?>" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="bulk-actions mb-3">
    <div class="dropdown d-inline-block">
        <button class="btn btn-outline-secondary dropdown-toggle bulk-actions-btn disabled" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Bulk Actions <span class="selected-count">0</span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
            <li>
                <button type="button" class="dropdown-item bulk-action-activate">
                    <i class="fas fa-check-circle me-1"></i> Activate
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item bulk-action-deactivate">
                    <i class="fas fa-times-circle me-1"></i> Deactivate
                </button>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <button type="button" class="dropdown-item bulk-action-delete text-danger">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </li>
        </ul>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="40">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all-items">
                    </div>
                </th>
                <th width="80">Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Views</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($products) > 0): ?>                <?php foreach($products as $product): ?>
                    <?php 
                        // Use main_image from the join instead of the old image column
                        $image = $product->main_image;
                        
                        $category_name = 'Uncategorized';
                        foreach ($categories as $cat) {
                            if ($cat->category_id == $product->category_id) {
                                $category_name = $cat->name;
                                break;
                            }
                        }
                    ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input item-checkbox" type="checkbox" value="<?php echo $product->product_id; ?>">
                            </div>
                        </td>
                        <td class="product-image-cell">
                            <?php if($image): ?>
                                <img src="<?php echo my_url('/uploads/products/' . $image); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="no-image bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($product->name); ?></div>
                            <div class="text-muted small">ID: <?php echo $product->product_id; ?></div>
                        </td>
                        <td><?php echo $category_name; ?></td>
                        <td>R<?php echo number_format($product->price, 2); ?></td>
                        <td><?php echo $product->stock; ?></td>
                        <td>
                            <?php if($product->status === 'active'): ?>
                                <span class="status-badge active">Active</span>
                            <?php elseif($product->status === 'inactive'): ?>
                                <span class="status-badge inactive">Inactive</span>
                            <?php else: ?>
                                <span class="status-badge pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->views ?? 0; ?></td>                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo my_url('/products/' . $product->product_id); ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo my_url('/seller/products/edit/' . $product->product_id); ?>" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger delete-product-btn" 
                                        data-id="<?php echo $product->product_id; ?>" 
                                        data-name="<?php echo htmlspecialchars($product->name); ?>" 
                                        data-bs-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="display-6 text-muted mb-3"><i class="fas fa-box-open"></i></div>
                        <h3>No products found</h3>
                        <p class="text-muted">You haven't added any products yet. Click the button below to add your first product.</p>
                        <a href="<?php echo my_url('/seller/products/create'); ?>" class="btn btn-primary mt-2">
                            <i class="fas fa-plus-circle me-1"></i> Add New Product
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if($total_pages > 1): ?>
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="pagination-info">
        <?php 
        $starting = ($page - 1) * $per_page + 1;
        $ending = min($starting + $per_page - 1, $total_products);
        ?>
        <span>Showing <?php echo $starting; ?> to <?php echo $ending; ?> of <?php echo $total_products; ?> products</span>
    </div>
    
    <nav aria-label="Product pagination">
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo my_url('/seller/products?page=1&search='.$search.'&category='.$category_filter.'&status='.$status_filter); ?>" aria-label="First">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo my_url('/seller/products?page='.($page-1).'&search='.$search.'&category='.$category_filter.'&status='.$status_filter); ?>">
                    Previous
                </a>
            </li>
            
            <?php
            // Display a limited number of page links
            $max_links = 5;
            $start_page = max(1, min($page - floor($max_links / 2), $total_pages - $max_links + 1));
            $end_page = min($total_pages, $start_page + $max_links - 1);
            
            if ($start_page > 1): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
            
            <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo my_url('/seller/products?page='.$i.'&search='.$search.'&category='.$category_filter.'&status='.$status_filter); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <?php if ($end_page < $total_pages): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
            
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo my_url('/seller/products?page='.($page+1).'&search='.$search.'&category='.$category_filter.'&status='.$status_filter); ?>">
                    Next
                </a>
            </li>
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo my_url('/seller/products?page='.$total_pages.'&search='.$search.'&category='.$category_filter.'&status='.$status_filter); ?>" aria-label="Last">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the product "<span class="product-name"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Product</button>
            </div>
        </div>
    </div>
</div>

<form id="product-delete-form" action="" method="POST" data-baseurl="<?php echo my_url('/seller/products/delete/__ID__'); ?>">
    <?php echo csrf_field(); ?>
</form>

<form id="bulk-action-form" action="" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="product_ids" id="bulk-product-ids" value="">
    <input type="hidden" name="action_type" id="bulk-action-type" value="">
</form>

<script>
 
    var bulkActionBaseUrl = '<?php echo my_url('/seller/products/bulk-action'); ?>';
</script>

<script src="<?php echo my_url('/assets/js/index_seller.js'); ?>"></script>

<?php include(resource_path('views/layouts/seller_dashboard_footer.php')); ?>