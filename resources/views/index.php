<?php
// Add custom CSS for the registration popup
$custom_css = isset($custom_css) ? $custom_css : '';
$custom_css .= '
<style>
    #winPopupOverlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1049;
        display: ' . (isset($_GET['registration']) && $_GET['registration'] == 'success' ? 'block' : 'none') . ';
    }
    
    #windowsPopup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 450px;
        z-index: 1050;
        overflow: hidden;
        display: ' . (isset($_GET['registration']) && $_GET['registration'] == 'success' ? 'block' : 'none') . ';
    }
    
    .popup-header {
        background-color: #2c3e50;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-content {
        display: flex;
        align-items: center;
    }
    
    .header-content i {
        margin-right: 10px;
        color: #4cd964;
    }
    
    .popup-header button {
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
    }
    
    .popup-body {
        padding: 20px;
        text-align: center;
    }
    
    .success-icon {
        font-size: 50px;
        color: #4cd964;
        margin-bottom: 20px;
    }
    
    .popup-message {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .popup-detail {
        color: #666;
    }
    
    .popup-footer {
        padding: 15px;
        text-align: center;
        border-top: 1px solid #eee;
    }
    
    .popup-button {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 8px 30px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
    }
    
    .popup-button:hover {
        background-color: #1a252f;
    }
</style>';

include(resource_path('views/layouts/header.php'));
?>

<?php if(isset($_GET['registration']) && $_GET['registration'] == 'success'): ?>
<!-- Registration success popup -->
<div id="windowsPopup">
    <div class="popup-header">
        <div class="header-content">
            <i class="fas fa-check-circle"></i>
            <span>Registration Successful</span>
        </div>
        <button onclick="closeWinPopup()">&times;</button>
    </div>
    <div class="popup-body">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <p class="popup-message">
            <?php echo session()->has('registered_name') ? 'Welcome ' . htmlspecialchars(session('registered_name')) . '!' : 'Your account has been created successfully!'; ?>
        </p>
        <p class="popup-detail">
            You can now browse products, make purchases, and enjoy our services.
        </p>
    </div>
    
    <div class="popup-footer">
        <button onclick="closeWinPopup()" class="popup-button">
            OK
        </button>
    </div>
</div>
<div id="winPopupOverlay"></div>

<script>
function closeWinPopup() {
    document.getElementById('windowsPopup').style.display = 'none';
    document.getElementById('winPopupOverlay').style.display = 'none';
    
    const url = new URL(window.location);
    url.searchParams.delete('registration');
    window.history.pushState({}, '', url);
}
</script>

<?php 
session()->forget(['registration_success', 'registered_name']); 
?>
<?php endif; ?>

<div class="hero-section text-white py-5" style="background-color: #2c3e50;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Welcome to Our C2C Marketplace</h1>
                <p class="lead mb-4">Your one-stop destination for consumer-to-consumer trading. Our platform connects buyers and sellers directly, creating a vibrant community where you can buy unique items or start your own selling journey.</p>
                <p class="mb-4">Whether you're looking to find great deals or turn your unused items into cash, our marketplace provides a secure and user-friendly environment for all your trading needs.</p>
                <?php if (!auth()->check()) { ?>
                    <a href="<?php echo my_url('/register'); ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Get Started
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Shop by Category</h2>
            </div>
        </div>
        
        <?php if(isset($categories) && count($categories) > 0): ?>
            <div class="row g-4">
                <?php foreach($categories as $category): ?>
                    <div class="col-6 col-md-3 mb-4">
                        <a href="<?php echo my_url('/products?category=' . $category->category_id); ?>" class="category-card d-flex flex-column align-items-center">
                            <div class="category-icon mb-3">
                                <i class="fas fa-<?php echo getCategoryIcon($category->category_name); ?> fa-3x"></i>
                            </div>
                            <h5 class="category-name"><?php echo htmlspecialchars($category->category_name); ?></h5>
                            <span class="badge bg-primary rounded-pill mt-2"><?php echo $category->product_count ?? 0; ?> products</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> Categories will appear here once available.
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="featuredProducts" class="section section-light py-5">
    <div class="container">        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Featured Products</h2>
            <a href="<?php echo my_url('/products'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-chevron-right me-1"></i> View All
            </a>
        </div>
        
        <?php if(isset($featured_products) && count($featured_products) > 0): ?>
            <div class="row g-4">
                <?php foreach($featured_products as $product): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100 shadow-sm">
                            <div class="product-image">
                                <a href="<?php echo my_url('/products/' . $product->product_id); ?>">
                                    <img src="<?php echo asset('uploads/products/' . ($product->image ?: 'default.jpg')); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name); ?>" 
                                         class="card-img-top">
                                </a>
                                <?php if(isset($product->condition_type) && $product->condition_type == 'New'): ?>
                                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="product-title">
                                    <a href="<?php echo my_url('/products/' . $product->product_id); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($product->name); ?>
                                    </a>
                                </h5>
                                <div class="product-price mb-2">
                                    <span class="fw-bold text-dark">R<?php echo number_format($product->price, 2); ?></span>
                                </div>                                <div class="product-seller mb-3">
                                    <small class="text-muted">Sold by <?php echo htmlspecialchars($product->seller_name); ?></small>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> Products will appear here once available.
            </div>
        <?php endif; ?>
    </div>
</div>
    </div>
</div>

<?php

function getCategoryIcon($categoryName) {
    $name = strtolower($categoryName);
    
    $iconMap = [
        'electronics' => 'laptop',
        'clothing' => 'tshirt',
        'home' => 'home',
        'garden' => 'leaf',
        'furniture' => 'couch',
        'toys' => 'gamepad',
        'books' => 'book',
        'sports' => 'futbol',
        'beauty' => 'spa',
        'health' => 'heartbeat',
        'automotive' => 'car',
        'jewelry' => 'gem'
    ];
    
    foreach ($iconMap as $key => $icon) {
        if (strpos($name, $key) !== false) {
            return $icon;
        }
    }
    
    return 'box';
}
?>
<script>
function closeWinPopup() {
    document.getElementById('windowsPopup').style.display = 'none';
    document.getElementById('winPopupOverlay').style.display = 'none';

    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.pushState({path:newurl}, '', newurl);
    }
}
</script>

<?php
include(resource_path('views/layouts/footer.php'));
?>
<script src="<?php echo asset('assets/js/index.js'); ?>"></script>
