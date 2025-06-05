<?php
include(resource_path('views/layouts/admin_header.php'));

$admin = auth()->user();
$admin_stats = [
    'total_users' => DB::table('users')->count(),
    'total_sellers' => DB::table('users')->where('role_id', 2)->count(),
    'total_buyers' => DB::table('users')->where('role_id', 1)->count(),
    'total_products' => DB::table('products')->count(),
    'total_orders' => DB::table('orders')->count()
];
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/profile.css'); ?>">

<div class="admin-content-main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Admin Profile</h1>
        <a href="<?php echo my_url('/admin'); ?>" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>
    </div><div class="profile-container">
        <div class="row">
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
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="profile-header">                        <div class="profile-avatar">                            <?php if (!empty($admin->profile_image)): ?>
                                <img src="<?php echo my_url('/uploads/profile_pictures/' . $admin->profile_image); ?>" alt="Profile Image">
                            <?php else: ?>
                                <div class="avatar-placeholder admin">
                                    <?php echo strtoupper(substr($admin->name, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($admin->name); ?></h3>
                        <p class="role-badge admin">Administrator</p>
                        <p class="join-date">Admin since <?php echo date('F Y', strtotime($admin->created_at)); ?></p>
                    </div>

                    <div class="profile-stats">
                        <h4>System Overview</h4>
                        <div class="stat-item">
                            <span class="stat-label">Total Users</span>
                            <span class="stat-value"><?php echo $admin_stats['total_users']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Sellers</span>
                            <span class="stat-value"><?php echo $admin_stats['total_sellers']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Buyers</span>
                            <span class="stat-value"><?php echo $admin_stats['total_buyers']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Products</span>
                            <span class="stat-value"><?php echo $admin_stats['total_products']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Orders</span>
                            <span class="stat-value"><?php echo $admin_stats['total_orders']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="profile-details">
                    <div class="section-header">
                        <h2>Administrator Information</h2>
                        <button class="btn btn-primary" onclick="toggleEditMode()">
                            Edit Profile
                        </button>
                    </div>

                    <form id="admin-profile-form" action="<?php echo my_url('/admin/profile/update'); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($admin->name); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($admin->email); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($admin->phone ?? ''); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input type="text" class="form-control" id="department" name="department" 
                                           value="<?php echo htmlspecialchars($admin->department ?? 'Administration'); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="image-group" style="display: none;">
                            <label for="profile_image">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        </div>

                        <div class="form-actions" id="form-actions" style="display: none;">
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>

                    <div class="section-divider"></div>
                    <div class="section-header">
                        <h3>Security Settings</h3>
                    </div>

                    <form id="admin-password-form" action="<?php echo my_url('/admin/profile/password'); ?>" method="POST">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    const inputs = document.querySelectorAll('#admin-profile-form input');
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
        editBtn.innerHTML = 'Cancel Edit';
    } else {
        formActions.style.display = 'none';
        imageGroup.style.display = 'none';
        editBtn.innerHTML = 'Edit Profile';
    }
}

function cancelEdit() {
    location.reload();
}
</script>

<?php include(resource_path('views/layouts/admin_footer.php')); ?>