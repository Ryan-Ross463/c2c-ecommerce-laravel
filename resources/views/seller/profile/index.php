<?php
$breadcrumbs = [
    'Profile' => null
];
include(resource_path('views/layouts/seller_dashboard_header.php'));

// Get seller data
$seller = auth()->user();
$seller_stats = [
    'total_products' => DB::table('products')->where('seller_id', $seller->user_id)->count(),
    'active_products' => DB::table('products')->where('seller_id', $seller->user_id)->where('status', 'active')->count(),
    // Fix: orders table doesn't have seller_id, it uses user_id for the buyer
    // We need to get orders for products that belong to this seller
    'total_sales' => DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.product_id')
        ->where('products.seller_id', $seller->user_id)
        ->count(),
    'total_revenue' => DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.product_id')
        ->where('products.seller_id', $seller->user_id)
        ->sum(DB::raw('order_items.quantity * order_items.price')) ?? 0
];

// Get recent orders for products sold by this seller
$recent_orders = DB::table('order_items')
    ->join('products', 'order_items.product_id', '=', 'products.product_id')
    ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
    ->join('users', 'orders.user_id', '=', 'users.user_id')
    ->where('products.seller_id', $seller->user_id)
    ->select('orders.*', 'users.name as buyer_name', 'products.name as product_name', 'order_items.quantity', 'order_items.price')
    ->orderBy('orders.created_at', 'desc')
    ->limit(5)
    ->get();
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/profile.css'); ?>">

<div class="profile-container">
    <div class="row">
        <!-- Success/Error Messages -->
        <?php if (session('success')): ?>
            <div class="col-12 mb-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo session('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="col-12 mb-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo session('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
        <!-- Profile Info Card -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-header">                    <div class="profile-avatar">                        <?php if (!empty($seller->profile_image)): ?>
                            <img src="<?php echo my_url('/uploads/profile_pictures/' . $seller->profile_image); ?>" alt="Profile Image">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?php echo strtoupper(substr($seller->name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <div class="upload-overlay">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h3><?php echo htmlspecialchars($seller->name); ?></h3>
                    <p class="role-badge seller">Seller</p>
                    <p class="join-date">Member since <?php echo date('F Y', strtotime($seller->created_at)); ?></p>
                </div>

                <div class="profile-stats">
                    <h4>Quick Stats</h4>
                    <div class="stat-item">
                        <span class="stat-label">Total Products</span>
                        <span class="stat-value"><?php echo $seller_stats['total_products']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Active Products</span>
                        <span class="stat-value"><?php echo $seller_stats['active_products']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total Sales</span>
                        <span class="stat-value"><?php echo $seller_stats['total_sales']; ?></span>
                    </div>                    <div class="stat-item">
                        <span class="stat-label">Revenue</span>
                        <span class="stat-value">R<?php echo number_format($seller_stats['total_revenue'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="profile-details">
                <div class="section-header">
                    <h2>Profile Information</h2>
                    <button class="btn btn-primary" onclick="toggleEditMode()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>

                <form id="profile-form" action="<?php echo my_url('/seller/profile/update'); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($seller->name); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($seller->email); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($seller->phone ?? ''); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?php echo htmlspecialchars($seller->location ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">About Me</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4" readonly><?php echo htmlspecialchars($seller->bio ?? ''); ?></textarea>
                    </div>

                    <div class="form-group" id="image-group" style="display: none;">
                        <label for="profile_image">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        <small class="text-muted">Max file size: 2MB. Supported formats: JPG, PNG, JPEG</small>
                    </div>

                    <div class="form-actions" id="form-actions" style="display: none;">
                        <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>

                <!-- Change Password Section -->
                <div class="section-divider"></div>
                <div class="section-header">
                    <h3>Change Password</h3>
                </div>

                <form id="password-form" action="<?php echo my_url('/seller/profile/password'); ?>" method="POST">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">Change Password</button>
                </form>

                <!-- Recent Sales Section -->
                <div class="section-divider"></div>
                <div class="section-header">
                    <h3>Recent Sales</h3>
                </div>

                <div class="recent-orders">
                    <h4>Recent Sales</h4>
                    <?php if(count($recent_orders) > 0): ?>
                        <?php foreach($recent_orders as $order): ?>
                            <div class="order-item">
                                <div class="order-details">
                                    <div class="order-product-name"><?php echo htmlspecialchars($order->product_name); ?></div>
                                    <div class="order-meta">
                                        Buyer: <?php echo htmlspecialchars($order->buyer_name); ?> | 
                                        Qty: <?php echo $order->quantity; ?> | 
                                        Date: <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                                    </div>
                                </div>
                                <div class="order-amount">
                                    R<?php echo number_format($order->price * $order->quantity, 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent sales to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    const inputs = document.querySelectorAll('#profile-form input, #profile-form textarea');
    const formActions = document.getElementById('form-actions');
    const imageGroup = document.getElementById('image-group');
    const editBtn = document.querySelector('.section-header .btn-primary');
    
    inputs.forEach(input => {
        if (input.name !== '_token') {
            input.readOnly = !input.readOnly;
        }
    });
    
    if (formActions.style.display === 'none') {
        formActions.style.display = 'block';
        imageGroup.style.display = 'block';
        editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel Edit';
    } else {
        formActions.style.display = 'none';
        imageGroup.style.display = 'none';
        editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
    }
}

function cancelEdit() {
    location.reload();
}

// Password confirmation validation
document.getElementById('password-form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New password and confirm password do not match.');
    }
});
</script>

<?php include(resource_path('views/layouts/seller_dashboard_footer.php')); ?>