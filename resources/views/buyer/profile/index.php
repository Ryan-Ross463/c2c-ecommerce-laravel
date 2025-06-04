<?php
if (!auth()->check() || auth()->user()->role_id != 1) {
    header('Location: ' . my_url('/login'));
    exit;
}

$buyer = auth()->user();
$buyer_stats = [
    'total_orders' => DB::table('orders')->where('user_id', $buyer->user_id)->count(),
    'completed_orders' => DB::table('orders')->where('user_id', $buyer->user_id)->where('status', 'delivered')->count(),
    'pending_orders' => DB::table('orders')->where('user_id', $buyer->user_id)->where('status', 'pending')->count(),
    'total_spent' => DB::table('orders')->where('user_id', $buyer->user_id)->sum('total_amount') ?? 0
];

$recent_orders = DB::table('orders')
    ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
    ->join('products', 'order_items.product_id', '=', 'products.product_id')
    ->where('orders.user_id', $buyer->user_id)
    ->select('orders.*', 'products.name as product_name', 'products.image', 'order_items.quantity', 'order_items.price')
    ->orderBy('orders.created_at', 'desc')
    ->limit(5)
    ->get();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo config('app.name'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('assets/css/profile.css'); ?>">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .profile-container {
            margin-top: 100px;
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo my_url('/'); ?>">
                <i class="fas fa-shopping-bag text-primary me-2"></i>
                <?php echo config('app.name', 'C2C eCommerce'); ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo my_url('/'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo my_url('/products'); ?>">Browse Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo my_url('/buyer/orders'); ?>">My Orders</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($buyer->name); ?>
                        </a>                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="<?php echo my_url('/buyer/profile'); ?>">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo my_url('/buyer/orders'); ?>">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo my_url('/logout'); ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    <div class="profile-container">
        <div class="container">
          
            <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo session('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo session('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
             
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="profile-header">                            <div class="profile-avatar">
                                <?php if (!empty($buyer->profile_image)): ?>
                                    <img src="<?php echo my_url('/uploads/profile_pictures/' . $buyer->profile_image); ?>" alt="Profile Image">
                                <?php else: ?>
                                    <div class="avatar-placeholder buyer">
                                        <?php echo strtoupper(substr($buyer->name, 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="upload-overlay">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <h3><?php echo htmlspecialchars($buyer->name); ?></h3>
                            <p class="role-badge buyer">Buyer</p>
                            <p class="join-date">Member since <?php echo date('F Y', strtotime($buyer->created_at)); ?></p>
                        </div>

                        <div class="profile-stats">
                            <h4>Shopping Stats</h4>
                            <div class="stat-item">
                                <span class="stat-label">Total Orders</span>
                                <span class="stat-value"><?php echo $buyer_stats['total_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Completed Orders</span>
                                <span class="stat-value"><?php echo $buyer_stats['completed_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Pending Orders</span>
                                <span class="stat-value"><?php echo $buyer_stats['pending_orders']; ?></span>
                            </div>                            <div class="stat-item">
                                <span class="stat-label">Total Spent</span>
                                <span class="stat-value">R<?php echo number_format($buyer_stats['total_spent'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="profile-details">
                        <div class="section-header">
                            <h2>Profile Information</h2>
                            <button class="btn btn-primary" onclick="toggleEditMode()">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                        </div>

                        <form id="profile-form" action="<?php echo my_url('/buyer/profile/update'); ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($buyer->name); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($buyer->email); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($buyer->phone ?? ''); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" 
                                               value="<?php echo htmlspecialchars($buyer->address ?? ''); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" readonly><?php echo htmlspecialchars($buyer->bio ?? ''); ?></textarea>
                            </div>

                            <div class="edit-actions" style="display: none;">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="profile-section mt-4">
                        <div class="section-header">
                            <h3>Recent Orders</h3>
                            <a href="<?php echo my_url('/buyer/orders'); ?>" class="btn btn-outline-primary btn-sm">
                                View All Orders
                            </a>
                        </div>

                        <?php if (!empty($recent_orders)): ?>
                            <div class="orders-list">
                                <?php foreach ($recent_orders as $order): ?>
                                    <div class="order-item">
                                        <div class="order-image">
                                            <?php if (!empty($order->image)): ?>
                                                <img src="<?php echo my_url('/uploads/products/' . $order->image); ?>" alt="Product">
                                            <?php else: ?>
                                                <div class="placeholder-image">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="order-details">
                                            <h5><?php echo htmlspecialchars($order->product_name); ?></h5>
                                            <p class="order-meta">
                                                Order #<?php echo $order->order_id; ?> • 
                                                <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                                            </p>
                                            <span class="order-status status-<?php echo $order->status; ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </div>
                                        <div class="order-amount">
                                            $<?php echo number_format($order->total_amount, 2); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h4>No orders yet</h4>
                                <p class="text-muted">Start shopping to see your orders here!</p>
                                <a href="<?php echo my_url('/products'); ?>" class="btn btn-primary">Browse Products</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="profile-section mt-4">
                        <div class="section-header">
                            <h3>Security Settings</h3>
                        </div>
                        
                        <div class="security-options">
                            <button class="btn btn-outline-warning" onclick="togglePasswordChange()">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>

                        <div id="password-change-form" style="display: none;" class="mt-3">
                            <form action="<?php echo my_url('/buyer/profile/password'); ?>" method="POST">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="current_password">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>
                                    </div>                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="password-actions">
                                    <button type="submit" class="btn btn-warning me-2">
                                        <i class="fas fa-save"></i> Update Password
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="togglePasswordChange()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditMode() {
            const form = document.getElementById('profile-form');
            const inputs = form.querySelectorAll('input[readonly], textarea[readonly]');
            const editActions = document.querySelector('.edit-actions');
            const editButton = document.querySelector('.section-header .btn-primary');
            
            const isReadonly = inputs[0].hasAttribute('readonly');
            
            inputs.forEach(input => {
                if (isReadonly) {
                    input.removeAttribute('readonly');
                    input.classList.add('editable');
                } else {
                    input.setAttribute('readonly', true);
                    input.classList.remove('editable');
                }
            });
            
            if (isReadonly) {
                editActions.style.display = 'block';
                editButton.style.display = 'none';
            } else {
                editActions.style.display = 'none';
                editButton.style.display = 'inline-block';
            }
        }

        function cancelEdit() {
            location.reload();
        }

        function togglePasswordChange() {
            const form = document.getElementById('password-change-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
