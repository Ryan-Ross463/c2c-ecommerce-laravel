<?php
$custom_css = '<link rel="stylesheet" href="' . my_url('assets/css/show.css') . '?v=' . time() . '">';
$custom_js = '<script src="' . my_url('assets/js/show.js') . '?v=' . time() . '"></script>';
include(resource_path('views/layouts/header.php'));

function getProductProp($product, $prop, $default = '') {
    return isset($product->$prop) ? $product->$prop : $default;
}
?>

<div class="main-container my-4 animate-fade">
    <?php if(isset($product)): ?>
       
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">                <li class="breadcrumb-item"><a href="<?php echo my_url('/'); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo my_url('/products?category=' . $product->category_id); ?>"><?php echo htmlspecialchars($product->category_name); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product->name); ?></li>
            </ol>
        </nav>

        <div class="row">
           
            <div class="col-md-5 mb-4">                <div class="product-gallery animate-slide-up">                    <div class="product-main-image">                        <img src="<?php echo my_url('uploads/products/' . ($product->main_image ?: 'default.jpg')); ?>" 
                            alt="<?php echo htmlspecialchars($product->name); ?>" 
                            class="img-fluid rounded">
                    </div>
                    
                    <?php if(isset($product_images) && count($product_images) > 0): ?>
                    <div class="product-thumbnails">                        <div class="product-thumbnail active">                            <img src="<?php echo my_url('uploads/products/' . ($product->main_image ?: 'default.jpg')); ?>" 
                                alt="Main image">
                        </div><?php foreach($product_images as $image): ?>
                            <div class="product-thumbnail">
                                <img src="<?php echo my_url('uploads/products/' . $image->image_url); ?>" 
                                    alt="Product image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="product-details animate-slide-up">
                    <h1 class="product-title"><?php echo htmlspecialchars($product->name); ?></h1>
                    
                    <div class="product-meta">
                        <span class="badge bg-primary me-2"><?php echo htmlspecialchars($product->category_name); ?></span>
                        <?php if(isset($product->condition_type)): ?>
                            <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($product->condition_type); ?></span>
                        <?php endif; ?>
                        <span class="text-muted"><i class="fas fa-eye me-1"></i> <?php echo $product->views; ?> views</span>
                    </div>
                    
                    <div class="product-rating mb-3">
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
                        <span>(<?php echo $product->review_count ?? 0; ?> reviews)</span>
                    </div>
                      <div class="product-price">
                        <span class="current-price">R<?php echo number_format($product->price, 2); ?></span>
                        <?php if(isset($product->original_price) && $product->original_price > $product->price): ?>
                            <span class="original-price">R<?php echo number_format($product->original_price, 2); ?></span>
                            <?php 
                            $discount = round(($product->original_price - $product->price) / $product->original_price * 100);
                            ?>
                            <span class="product-discount">-<?php echo $discount; ?>%</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-description">
                        <h5>Description</h5>
                        <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                    </div>
                    
                    <div class="product-actions mb-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" id="decrementQty">-</button>
                                    <input type="number" class="form-control text-center" id="productQty" value="1" min="1" max="<?php echo $product->stock; ?>">
                                    <button class="btn btn-outline-secondary" type="button" id="incrementQty">+</button>
                                </div>
                            </div>                            <div class="col-6">
                                <span class="text-muted"><?php echo $product->stock; ?> items in stock</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-actions mb-4">
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="button" class="btn btn-primary flex-grow-1" id="addToCartBtn">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                            <button type="button" class="btn btn-outline-primary product-wishlist" id="saveForLaterBtn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="seller-info mb-4">
                        <h5 class="card-title">Seller Information</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="seller-avatar me-3">
                                <i class="fas fa-user-circle fa-3x text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($product->seller_name); ?></h6>
                                <p class="mb-0 text-muted small">
                                    <i class="fas fa-calendar-alt me-1"></i> Member since <?php echo date('M Y', strtotime($product->seller_joined)); ?>
                                </p>
                            </div>                        </div>
                        <button onclick="showMessageNotImplemented()" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-comment-alt me-2"></i>Contact Seller
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(isset($related_products) && count($related_products) > 0): ?>
            <div class="related-products mt-5 animate-fade">
                <h3 class="section-title">Related Products</h3>
                <div class="product-grid">
                    <?php foreach($related_products as $related): ?>
                        <div class="product-card animate-slide-up">
                            <div class="product-image">
                                <button class="product-wishlist" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>                                <a href="<?php echo my_url('/products/' . $related->product_id); ?>">                                    <img src="<?php echo my_url('uploads/products/' . ($related->main_image ?: 'default.jpg')); ?>" 
                                        alt="<?php echo htmlspecialchars($related->name); ?>">
                                </a>
                                <?php if(isset($related->condition_type) && $related->condition_type == 'New'): ?>
                                    <span class="product-badge new">New</span>
                                <?php endif; ?>
                                <div class="product-actions">
                                    <button class="product-actions-btn">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                            <div class="product-content">
                                <h5 class="product-title">                                    <a href="<?php echo my_url('/products/' . $related->product_id); ?>">
                                        <?php echo htmlspecialchars($related->name); ?>
                                    </a>
                                </h5>                                <div class="product-price">
                                    <div class="price-current">R<?php echo number_format($related->price, 2); ?></div>
                                </div>
                                <div class="product-seller">
                                    Sold by <a href="<?php echo my_url('/sellers/' . $related->seller_id); ?>"><?php echo htmlspecialchars($related->seller_name); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <h4>Product Not Found</h4>
            <p>Sorry, the product you're looking for doesn't exist or has been removed.</p>
            <a href="<?php echo my_url('/products'); ?>" class="btn btn-primary">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product data
    const productData = {
        productId: <?php echo $product->product_id ?? 0; ?>,
        maxStock: <?php echo $product->stock ?? 0; ?>
    };
    
    window.productData = productData;
});

// Function to show not implemented message
function showMessageNotImplemented() {
    alert('This section will be implemented later');
}
</script>

<?php
include(resource_path('views/layouts/footer.php'));
?>
