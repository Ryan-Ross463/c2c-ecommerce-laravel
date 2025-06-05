<?php 
if (!function_exists('my_url')) {
    function my_url($path = null) {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
        }
        $path = $path ? '/' . ltrim($path, '/') : '';
        return $baseUrl . $path;
    }
}

include(resource_path('views/layouts/admin_header.php'));
?>

<div class="admin-dashboard container-fluid py-4">
    <h1 class="mb-4">Welcome to Admin Dashboard, <?php echo htmlspecialchars($admin->name ?? $admin['name'] ?? 'Admin'); ?></h1>
      <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="admin-stat-card card text-center p-4 h-100">
                <i class="fas fa-users stat-icon"></i>
                <h3>Users</h3>
                <p class="admin-stat-number display-4"><?php echo $user_count; ?></p>
                <a href="<?php echo my_url('admin/users/manage_users'); ?>" class="btn btn-primary mt-auto">Manage Users</a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="admin-stat-card card text-center p-4 h-100">
                <i class="fas fa-shopping-bag stat-icon"></i>
                <h3>Products</h3>
                <p class="admin-stat-number display-4"><?php echo $product_count; ?></p>
                <a href="<?php echo my_url('admin/products'); ?>" class="btn btn-primary mt-auto">View Products</a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="admin-stat-card card text-center p-4 h-100">
                <i class="fas fa-shopping-cart stat-icon"></i>
                <h3>Orders</h3>
                <p class="admin-stat-number display-4"><?php echo $order_count; ?></p>
                <a href="<?php echo my_url('admin/reports/orders'); ?>" class="btn btn-primary mt-auto">View Orders</a>
            </div>
        </div>
      </div>
        <div class="row mb-4">
        <div class="col-md-12">
            <div class="admin-quick-links card p-4">
                <h2 class="mb-4">Quick Actions</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo my_url('admin/users/create_admin'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Create Admin User
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo my_url('admin/reports/sales'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-chart-line me-2"></i> View Sales Reports
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo my_url('admin/reports/user_activity'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-clock me-2"></i> User Activity
                        </a>
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>

<?php
include(resource_path('views/layouts/admin_footer.php'));
?>
