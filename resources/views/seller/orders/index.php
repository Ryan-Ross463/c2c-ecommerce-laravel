<?php
// filepath: c:\xampp\htdocs\c2c_ecommerce\C2C_ecommerce_laravel\resources\views\seller\orders\index.php

$page_title = 'Seller Orders';
include(resource_path('views/layouts/seller_dashboard_header.php'));

// Add breadcrumbs
$breadcrumbs = [
    'Orders' => false
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart me-2"></i> Orders
            </h1>
            <p class="text-muted">Manage your orders</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center py-5">
                        <img src="<?php echo my_url('/assets/images/coming-soon.svg'); ?>" 
                             alt="Coming Soon" 
                             class="img-fluid mb-4" 
                             style="max-width: 250px; opacity: 0.7;"
                             onerror="this.src='<?php echo my_url('/assets/images/no-results.svg'); ?>'">
                             
                        <h2 class="h3 mb-3">Orders Management</h2>
                        <p class="lead text-muted mb-4">This section will be implemented later.</p>
                        
                        <div class="features-preview mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-4 mb-4">
                                    <div class="feature-card">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-list-alt"></i>
                                        </div>
                                        <h5>View Orders</h5>
                                        <p>Track all orders from your customers</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="feature-card">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <h5>Manage Shipments</h5>
                                        <p>Update order statuses and shipping information</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="feature-card">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <h5>Sales Analytics</h5>
                                        <p>View detailed reports of your sales performance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?php echo my_url('/seller/products'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    background-color: rgba(13, 110, 253, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.icon-wrapper i {
    font-size: 24px;
    color: #0d6efd;
}

.feature-card h5 {
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.feature-card p {
    color: #6c757d;
    margin-bottom: 0;
}
</style>

<?php
include(resource_path('views/layouts/seller_dashboard_footer.php'));
?>
