<?php
$custom_css = '
    <link rel="stylesheet" href="' . my_url('assets/css/index_products.css') . '?v=' . time() . '">
';
$custom_js = '
    <script src="' . my_url('assets/js/index_products.js') . '?v=' . time() . '"></script>
';
include(resource_path('views/layouts/header.php'));
?>

<?php if(isset($error_message)): ?>
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
    </div>
<?php endif; ?>

<div class="main-container my-4">       
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="product-section-title"><?php if (isset($current_category) && isset($categories)): ?>
                            <?php 
                            $category_name = 'All Products';
                            foreach ($categories as $cat) {
                                if ($cat->category_id == $current_category) {
                                    $category_name = $cat->category_name;
                                    break;
                                }
                            }
                            echo htmlspecialchars($category_name); 
                            ?>
                        <?php else: ?>
                            All Products
                        <?php endif; ?>
                    </h4>
                    <?php if (isset($products_count)): ?>
                        <div class="text-muted">Showing <?php echo count($products); ?> of <?php echo $products_count; ?> products</div>
                    <?php endif; ?>
                </div>
                <div class="view-options btn-group d-none d-md-flex">
                    <button type="button" class="btn btn-outline-primary btn-sm view-grid active" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm view-list" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            
            <div class="products-grid active animate-fade">
                <div class="product-grid">
                    <?php if(isset($products) && count($products) > 0): ?>
                        <?php foreach($products as $product): ?>
                            <div class="product-card animate-slide-up">
                                <div class="product-image">
                                    <button class="product-wishlist" title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>                                    <a href="<?php echo my_url('/products/' . $product->product_id); ?>">
                                        <img src="<?php echo my_url('uploads/products/' . ($product->main_image ?: 'default.jpg')); ?>" 
                                            alt="<?php echo htmlspecialchars($product->name); ?>">
                                    </a>
                                    <?php if(isset($product->condition_type) && $product->condition_type == 'New'): ?>
                                        <span class="product-badge new">New</span>
                                    <?php elseif(isset($product->views) && $product->views > 100): ?>
                                        <span class="product-badge hot">Hot</span>
                                    <?php endif; ?>
                                    <div class="product-actions">
                                        <button class="product-actions-btn">
                                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <?php if(isset($product->category_name)): ?>
                                        <div class="product-category"><?php echo htmlspecialchars($product->category_name); ?></div>
                                    <?php endif; ?>
                                    <h5 class="product-title">
                                        <a href="<?php echo my_url('/products/' . $product->product_id); ?>">
                                            <?php echo htmlspecialchars($product->name); ?>
                                        </a>
                                    </h5>
                                    <div class="product-rating">
                                        <?php 
                                        $rating = isset($product->rating) ? round($product->rating) : 0;
                                        for($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <?php if($i <= $rating): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span>(<?php echo $product->review_count ?? 0; ?>)</span>
                                    </div>
                                    <div class="product-price">
                                        <div class="price-current">$<?php echo number_format($product->price, 2); ?></div>
                                        <?php if(isset($product->original_price) && $product->original_price > $product->price): ?>
                                            <div class="price-original">$<?php echo number_format($product->original_price, 2); ?></div>
                                            <?php 
                                            $discount = round(($product->original_price - $product->price) / $product->original_price * 100);
                                            ?>
                                            <div class="price-discount">-<?php echo $discount; ?>%</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-seller">
                                        Sold by <a href="<?php echo my_url('/sellers/' . $product->seller_id); ?>"><?php echo htmlspecialchars($product->seller_name); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="text-center py-5">
                                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                                    <h4>No Products Found</h4>
                                    <p class="text-muted">We couldn't find any products matching your criteria.</p>
                                    <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-primary mt-2">
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="products-list animate-fade">
                <?php if(isset($products) && count($products) > 0): ?>
                    <?php foreach($products as $product): ?>
                        <div class="product-list-item animate-slide-up">                            <div class="product-list-image">
                                <img src="<?php echo my_url('uploads/products/' . ($product->main_image ?: 'default.jpg')); ?>" 
                                     alt="<?php echo htmlspecialchars($product->name); ?>">
                                <?php if(isset($product->condition_type) && $product->condition_type == 'New'): ?>
                                    <span class="product-badge new">New</span>
                                <?php elseif(isset($product->views) && $product->views > 100): ?>
                                    <span class="product-badge hot">Hot</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-list-content">
                                <div class="product-list-header">
                                    <h5 class="product-list-title">
                                        <a href="<?php echo my_url('/products/' . $product->product_id); ?>">
                                            <?php echo htmlspecialchars($product->name); ?>
                                        </a>
                                    </h5>
                                    <div class="product-list-badges">
                                        <?php if(isset($product->in_stock) && $product->in_stock): ?>
                                            <span class="badge bg-success">In Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Limited Stock</span>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($product->condition_type)): ?>
                                            <span class="badge bg-light text-dark border"><?php echo $product->condition_type; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="product-rating mb-2">
                                    <?php 
                                    $rating = isset($product->rating) ? round($product->rating) : 0;
                                    for($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <?php if($i <= $rating): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span>(<?php echo $product->review_count ?? 0; ?>)</span>
                                </div>
                                
                                <p class="product-list-description">
                                    <?php echo htmlspecialchars(substr($product->description ?? '', 0, 150) . '...'); ?>
                                </p>
                                
                                <div class="product-list-footer">
                                    <div>
                                        <div class="product-list-price">$<?php echo number_format($product->price, 2); ?></div>
                                        <?php if(isset($product->original_price) && $product->original_price > $product->price): ?>
                                            <div class="price-discount">
                                                $<?php echo number_format($product->original_price, 2); ?> 
                                                <?php 
                                                $discount = round(($product->original_price - $product->price) / $product->original_price * 100);
                                                echo "(-{$discount}%)"; 
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="product-seller">
                                            Sold by <a href="<?php echo my_url('/sellers/' . $product->seller_id); ?>"><?php echo htmlspecialchars($product->seller_name); ?></a>
                                        </div>
                                    </div>
                                    
                                    <div class="product-list-actions">
                                        <a href="<?php echo my_url('/products/' . $product->product_id); ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                        <button class="btn btn-primary btn-sm ms-2">
                                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h4>No Products Found</h4>
                            <p class="text-muted">We couldn't find any products matching your criteria.</p>
                            <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-primary mt-2">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pagination-container">
                <?php if(isset($total_pages) && $total_pages > 1): ?>
                    <div class="pagination-nav">
                        <?php if (isset($current_page) && $current_page > 1): ?>
                            <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $current_page - 1]))); ?>" class="btn btn-outline-primary btn-prev">
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
                        
                        <?php if (isset($current_page) && isset($total_pages) && $current_page < $total_pages): ?>
                            <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $current_page + 1]))); ?>" class="btn btn-outline-primary btn-next">
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
                        $start_page = max(1, min($current_page - 2, $total_pages - 4));
                        $end_page = min($total_pages, max(5, $current_page + 2));
                        
                        if ($start_page > 1): ?>
                            <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>" class="page-number">1</a>
                            <?php if ($start_page > 2): ?>
                                <span class="page-ellipsis">...</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <a href="<?php echo my_url('/products?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>" 
                               class="page-number <?php echo isset($current_page) && $current_page == $i ? 'active' : ''; ?>">
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
                <?php endif; ?>            </div>
        </div>
    </div>
</div>

<?php
include(resource_path('views/layouts/footer.php'));
?>
