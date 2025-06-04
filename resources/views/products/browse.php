<?php 
$custom_css = '
    <link rel="stylesheet" href="' . my_url('assets/css/browse.css') . '?v=' . time() . '">
    <style>
        /* Override any conflicting styles from products.css */
        .filter-offcanvas {
            position: fixed !important;
            top: 0 !important;
            left: -320px !important;
            bottom: 0 !important;
            width: 320px !important;
            max-width: 90% !important;
            background: #fff !important;
            z-index: 1050 !important;
            transition: left 0.3s ease !important;
            overflow-y: auto !important;
            display: none !important;
            flex-direction: column !important;
        }
        
        .filter-offcanvas.show {
            left: 0 !important;
            display: flex !important;
        }
        
        .filter-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 1040 !important;
            display: none !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            backdrop-filter: blur(2px) !important;
        }
        
        .filter-backdrop.show {
            display: block !important; 
            opacity: 1 !important;
        }
        
        .filter-section .filter-header {
            position: relative !important;
            cursor: pointer !important;
            padding: 12px 15px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 0 !important;
            background-color: #f8f9fa !important;
        }
        
        .filter-section .filter-header i {
            transition: transform 0.3s !important;
        }
        
        .filter-offcanvas-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 15px !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background-color: #4a6cf7 !important;
            color: white !important;
        }
        
        .filter-close {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-size: 1.25rem !important;
            cursor: pointer !important;
        }
    </style>
';
$custom_js = '<script src="' . my_url('assets/js/browse.js') . '?v=' . time() . '"></script>';
include(resource_path('views/layouts/header.php'));
?>

<div class="container-fluid py-4">
   
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo my_url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo my_url('/products'); ?>">Products</a></li>
            <?php if(isset($category_name)): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($category_name); ?></li>
            <?php endif; ?>
        </ol>
    </nav>
    
    <?php if(isset($_GET['search']) || isset($_GET['category']) || isset($_GET['min_price']) || isset($_GET['max_price']) || isset($_GET['condition']) || isset($_GET['in_stock'])): ?>
        <div class="active-filters mb-4">
            <div class="d-flex align-items-center flex-wrap">
                <strong class="me-2">Active Filters:</strong>
                
                <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        Search: <?php echo htmlspecialchars($_GET['search']); ?>
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['search' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['category']) && !empty($_GET['category'])): ?>
                    <?php 
                    $category_name = '';
                    foreach($categories as $cat) {
                        if($cat->category_id == $_GET['category']) {
                            $category_name = $cat->category_name;
                            break;
                        }
                    }
                    ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        Category: <?php echo htmlspecialchars($category_name); ?>
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['category' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if((isset($_GET['min_price']) && $_GET['min_price'] > 0) || (isset($_GET['max_price']) && !empty($_GET['max_price']))): ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        Price: R<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '0'; ?> 
                        - R<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '1000+'; ?>
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['min_price' => null, 'max_price' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['condition']) && !empty($_GET['condition'])): ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        Condition: <?php echo htmlspecialchars($_GET['condition']); ?>
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['condition' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['in_stock']) && $_GET['in_stock'] == 1): ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        In Stock Only
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['in_stock' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['sort']) && $_GET['sort'] != 'newest'): ?>
                    <span class="active-filter badge bg-light text-dark m-1 p-2">
                        Sort: <?php 
                        switch($_GET['sort']) {
                            case 'price_low': echo 'Price: Low to High'; break;
                            case 'price_high': echo 'Price: High to Low'; break;
                            case 'popular': echo 'Most Popular'; break;
                            case 'rating': echo 'Rating'; break;
                            default: echo htmlspecialchars($_GET['sort']); break;
                        }
                        ?>
                        <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['sort' => null]))); ?>" class="ms-2 text-dark">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
                
                <a href="<?php echo my_url('/products'); ?>" class="btn btn-sm btn-outline-secondary ms-auto">
                    <i class="fas fa-times me-1"></i> Clear All
                </a>
            </div>
        </div>
    <?php endif; ?>   
    <div class="d-block d-lg-none mb-3">
        <button class="filter-toggle btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" id="mobile-filter-toggle" type="button">
            <i class="fas fa-filter me-2"></i> Show Filters
        </button>    </div>    <div class="filter-backdrop"></div>
    
    <div class="filter-offcanvas">
        <div class="filter-offcanvas-header">
            <h5>Filter Products</h5>
            <button type="button" class="filter-close" aria-label="Close filters">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="filter-offcanvas-body">
            <div class="card border-0">
                <div class="card-body">
                    <form action="<?php echo my_url('/products'); ?>" method="GET">                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#searchFilter" aria-expanded="true">
                                <span>Search</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>
                            <div id="searchFilter" class="collapse show filter-body"><div class="input-group">
                                    <input type="text" class="form-control" id="search" name="search" 
                                        placeholder="Search products, brands, etc." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#categoriesFilter" aria-expanded="true">
                                <span>Categories</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>
                            <div id="categoriesFilter" class="collapse show filter-body">
                                <div class="list-group list-group-flush">
                                    <a href="<?php echo my_url('/products' . ($search ? '?search=' . urlencode($search) : '')); ?>" 
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                        <?php echo !isset($current_category) ? 'active' : ''; ?>">
                                        All Categories
                                        <span class="badge bg-primary rounded-pill">
                                            <?php echo $total_products ?? 0; ?>
                                        </span>
                                    </a>
                                    <?php if(isset($categories) && count($categories) > 0): ?>
                                        <?php foreach($categories as $category): ?>
                                            <a href="<?php echo my_url('/products?category=' . $category->category_id . ($search ? '&search=' . urlencode($search) : '')); ?>" 
                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                                <?php echo (isset($current_category) && $current_category == $category->category_id) ? 'active' : ''; ?>">
                                                <?php echo htmlspecialchars($category->category_name); ?>
                                                <span class="badge bg-primary rounded-pill"><?php echo $category->product_count; ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#priceRangeFilter" aria-expanded="true">
                                <span>Price Range</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>
                            <div id="priceRangeFilter" class="collapse show filter-body">                                <div class="price-range-slider py-2">
                                    <input type="range" class="form-range mb-2" id="price_range" 
                                        min="0" max="1000" step="10" 
                                        value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '1000'; ?>">
                                    <div class="d-flex justify-content-between">
                                        <div class="input-group w-45">
                                            <span class="input-group-text">R</span>
                                            <input type="number" class="form-control" id="min_price" name="min_price" 
                                                placeholder="Min" min="0" 
                                                value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '0'; ?>">
                                        </div>
                                        <div class="input-group w-45">
                                            <span class="input-group-text">R</span>
                                            <input type="number" class="form-control" id="max_price" name="max_price" 
                                                placeholder="Max" min="0" 
                                                value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '1000'; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#conditionFilter" aria-expanded="true">
                                <span>Product Condition</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>
                            <div id="conditionFilter" class="collapse show filter-body">
                                <div class="py-2">
                                    <select class="form-select" name="condition">
                                        <option value="">All Conditions</option>
                                        <option value="New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'New' ? 'selected' : ''; ?>>New</option>
                                        <option value="Like New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Like New' ? 'selected' : ''; ?>>Like New</option>
                                        <option value="Good" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Good' ? 'selected' : ''; ?>>Good</option>
                                        <option value="Fair" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Fair' ? 'selected' : ''; ?>>Fair</option>
                                        <option value="Poor" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Poor' ? 'selected' : ''; ?>>Poor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                         
                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#sortByFilter" aria-expanded="true">
                                <span>Sort By</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>                            <div id="sortByFilter" class="collapse show filter-body">
                                <div class="py-2">
                                    <select class="form-select" name="sort">
                                        <option value="newest" <?php echo !isset($_GET['sort']) || $_GET['sort'] == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                        <option value="price_low" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                        <option value="price_high" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>

                                        <option value="popular" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                                    </select>
                                </div>
                            </div>
                        </div>                      
                        <div class="filter-section mb-3">
                            <h6 class="filter-header" data-bs-toggle="collapse" data-bs-target="#availabilityFilter" aria-expanded="true">
                                <span>Availability</span>
                                <i class="fas fa-chevron-down"></i>
                            </h6>
                            <div id="availabilityFilter" class="collapse show filter-body">
                                <div class="py-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockOnly"
                                            <?php echo isset($_GET['in_stock']) && $_GET['in_stock'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="inStockOnly">
                                            In Stock Only
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                        
                        <div class="applied-filters-summary mt-4">
                            <h6>Applied Filters <span id="filterCount" class="badge bg-primary rounded-pill">0</span></h6>
                            <div id="appliedFiltersList" class="applied-filter-tags">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    <div class="row">
       
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card filter-card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Filter Products</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo my_url('/products'); ?>" method="GET">
                       
                        <div class="mb-3">
                            <h6>Search</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="side-search" name="search" 
                                    placeholder="Search products" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Categories</h6>
                            <div class="list-group list-group-flush">
                                <a href="<?php echo my_url('/products' . ($search ? '?search=' . urlencode($search) : '')); ?>" 
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                    <?php echo !isset($current_category) ? 'active' : ''; ?>">
                                    All Categories
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo $total_products ?? 0; ?>
                                    </span>
                                </a>
                                <?php if(isset($categories) && count($categories) > 0): ?>
                                    <?php foreach($categories as $category): ?>
                                        <a href="<?php echo my_url('/products?category=' . $category->category_id . ($search ? '&search=' . urlencode($search) : '')); ?>" 
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                            <?php echo (isset($current_category) && $current_category == $category->category_id) ? 'active' : ''; ?>">
                                            <?php echo htmlspecialchars($category->category_name); ?>
                                            <span class="badge bg-primary rounded-pill"><?php echo $category->product_count; ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Sort By</h6>
                            <select class="form-select" id="sort" name="sort" onchange="this.form.submit()">
                                <option value="newest" <?php echo ($current_sort ?? '') == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="price_low" <?php echo ($current_sort ?? '') == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo ($current_sort ?? '') == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="popular" <?php echo ($current_sort ?? '') == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Price Range</h6>
                            <div class="price-range-slider py-2">
                                <input type="range" class="form-range mb-2" id="price_range_side" 
                                    min="0" max="1000" step="10" 
                                    value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '1000'; ?>">                                <div class="d-flex justify-content-between">
                                    <div class="input-group w-45">
                                        <span class="input-group-text">R</span>
                                        <input type="number" class="form-control" id="min_price_side" name="min_price" 
                                            placeholder="Min" min="0" 
                                            value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '0'; ?>">
                                    </div>
                                    <div class="input-group w-45">
                                        <span class="input-group-text">R</span>
                                        <input type="number" class="form-control" id="max_price_side" name="max_price" 
                                            placeholder="Max" min="0" 
                                            value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '1000'; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>                      
                        <div class="mb-3">
                            <h6>Product Condition</h6>
                            <div class="py-2">
                                <select class="form-select" name="condition">
                                    <option value="">All Conditions</option>
                                    <option value="New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'New' ? 'selected' : ''; ?>>New</option>
                                    <option value="Like New" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Like New' ? 'selected' : ''; ?>>Like New</option>
                                    <option value="Good" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Good' ? 'selected' : ''; ?>>Good</option>
                                    <option value="Fair" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Fair' ? 'selected' : ''; ?>>Fair</option>
                                    <option value="Poor" <?php echo isset($_GET['condition']) && $_GET['condition'] == 'Poor' ? 'selected' : ''; ?>>Poor</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Availability</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockOnly_side"
                                    <?php echo isset($_GET['in_stock']) && $_GET['in_stock'] == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="inStockOnly_side">
                                    In Stock Only
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0 product-section-title">Products</h4>
                    <?php if(isset($products_count)): ?>
                        <small class="text-muted"><?php echo $products_count; ?> items found</small>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center">
                    <a href="<?php echo my_url('/cart'); ?>" class="btn btn-outline-primary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(isset($cart_count) && $cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <div class="products-container">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php if(isset($products) && count($products) > 0): ?>
                        <?php foreach($products as $product): ?>
                            <div class="col">
                                <div class="card h-100 product-card">                                    <div class="product-image-container" style="height: 200px; overflow: hidden;">
                                        <a href="<?php echo my_url('/products/' . $product->product_id); ?>">
                                            <img src="<?php echo my_url('uploads/products/' . ($product->main_image ?: 'default.jpg')); ?>" 
                                                alt="<?php echo htmlspecialchars($product->name); ?>" 
                                                class="card-img-top h-100 w-100" style="object-fit: cover;">
                                        </a>
                                        <?php if($product->condition_type == 'New'): ?>
                                            <span class="position-absolute top-0 start-0 badge bg-success m-2">New</span>
                                        <?php endif; ?>
                                        <?php if(isset($product->stock) && $product->stock > 0): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-light text-dark m-2">In Stock</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-truncate">
                                            <a href="<?php echo my_url('/products/' . $product->product_id); ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($product->name); ?>
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted mb-2" style="min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo htmlspecialchars(substr($product->description, 0, 100) . '...'); ?>
                                        </p>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="h5 mb-0">R<?php echo number_format($product->price, 2); ?></span>
                                                <?php if(isset($product->original_price) && $product->original_price > $product->price): ?>
                                                    <span class="text-decoration-line-through text-muted">R<?php echo number_format($product->original_price, 2); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $product->product_id; ?>">
                                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="no-products-found text-center py-5">
                                <img src="<?php echo my_url('assets/images/no-results.svg'); ?>" alt="No products found" class="mb-3" style="max-width: 150px;">
                                <h4>No Products Found</h4>
                                <p class="text-muted">We couldn't find any products matching your criteria.</p>
                                <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-primary mt-3">Clear Filters</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if(isset($pagination) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="Product pagination" class="my-4">
                    <ul class="pagination justify-content-center">                        <li class="page-item <?php echo (int)$pagination['current_page'] == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>
                        <li class="page-item <?php echo (int)$pagination['current_page'] == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => max(1, (int)$pagination['current_page'] - 1)]))); ?>">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                          <?php 
                        $start_page = max(1, (int)$pagination['current_page'] - 2);
                        $end_page = min((int)$pagination['total_pages'], (int)$pagination['current_page'] + 2);
                        
                        for($i = $start_page; $i <= $end_page; $i++): 
                        ?>
                            <li class="page-item <?php echo $i == (int)$pagination['current_page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                          <li class="page-item <?php echo (int)$pagination['current_page'] == (int)$pagination['total_pages'] ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => min((int)$pagination['total_pages'], (int)$pagination['current_page'] + 1)]))); ?>">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item <?php echo (int)$pagination['current_page'] == (int)$pagination['total_pages'] ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => (int)$pagination['total_pages']]))); ?>">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

            <div class="container">
                <div class="pagination-container">
                    <?php if(isset($total_pages) && $total_pages > 1): ?>
                        <div class="pagination-nav">
                            <?php if (isset($current_page) && (int)$current_page > 1): ?>
                                <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => (int)$current_page - 1]))); ?>" class="btn btn-outline-primary btn-prev">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php else: ?>
                                <button disabled class="btn btn-outline-secondary btn-prev">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                            <?php endif; ?>
                            
                            <div class="pagination-info">
                                Showing <?php echo isset($showing_from) ? $showing_from : '0'; ?> 
                                to <?php echo isset($showing_to) ? $showing_to : '0'; ?> 
                                of <?php echo isset($total_products) ? $total_products : '0'; ?> results
                            </div>
                            
                            <?php if (isset($current_page) && isset($total_pages) && (int)$current_page < (int)$total_pages): ?>
                                <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => (int)$current_page + 1]))); ?>" class="btn btn-outline-primary btn-next">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <button disabled class="btn btn-outline-secondary btn-next">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="pagination-numbers">
                            <?php 
                            $start_page = max(1, min((int)$current_page - 2, (int)$total_pages - 4));
                            $end_page = min((int)$total_pages, max(5, (int)$current_page + 2));
                            
                            if ($start_page > 1): ?>
                                <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>" class="page-number">1</a>
                                <?php if ($start_page > 2): ?>
                                    <span class="page-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>" 
                                class="page-number <?php echo isset($current_page) && (int)$current_page == (int)$i ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <span class="page-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $total_pages]))); ?>" class="page-number">
                                    <?php echo $total_pages; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function my_url(path = '') {
    const baseUrl = window.location.origin;
    return baseUrl + (path ? '/' + path.replace(/^\//, '') : '');
}
</script>

<?php
include(resource_path('views/layouts/footer.php'));
?>