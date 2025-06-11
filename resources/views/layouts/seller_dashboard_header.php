<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(resource_path('views/layouts/header.php'));

if (!auth()->check()) {
   
    $redirect_url = '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']);
    header("Location: {$redirect_url}");
    exit;
}

$user = auth()->user();
$seller_id = $user->user_id;
$seller = $user;

if (!$seller) {
  
    header("Location: /login");
    exit;
}

$current_url = $_SERVER['REQUEST_URI'];
$active_menu = 'products'; 

if (strpos($current_url, '/seller/products') !== false) {
    $active_menu = 'products';
} elseif (strpos($current_url, '/seller/orders') !== false) {
    $active_menu = 'orders';
} elseif (strpos($current_url, '/seller/profile') !== false) {
    $active_menu = 'profile';
}
?>

<link href="/assets/css/seller-dashboard.css?v=<?php echo time(); ?>" rel="stylesheet">
<link href="/assets/css/seller-dashboard-footer.css?v=<?php echo time(); ?>" rel="stylesheet">

<style>
    .logout-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 0.5rem 1rem;
        color: #6c757d;
        background: transparent;
        border: none;
    }
    
    .logout-btn:hover {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }
</style>

<main class="seller-dashboard">
    <div class="container-fluid">
        <div class="row">
         
            <div class="col-lg-2 sidebar">
                <div class="sidebar-header">
                    <h3>Seller Dashboard</h3>
                </div>
                  <div class="sidebar-profile">                <div class="profile-image">
                        <?php if (!empty($seller->profile_image)): ?>
                            <img src="/uploads/profile_pictures/<?php echo $seller->profile_image; ?>" alt="Profile Image">
                        <?php else: ?>
                            <div class="profile-initial"><?php echo strtoupper(substr($seller->name, 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h5><?php echo $seller->name; ?></h5>
                        <p class="text-muted">Seller</p>
                    </div>
                </div>                  <ul class="sidebar-menu">
                    <li class="<?php echo $active_menu === 'products' ? 'active' : ''; ?>">
                        <a href="/seller/products">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="<?php echo $active_menu === 'orders' ? 'active' : ''; ?>">
                        <a href="/seller/orders">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>                    <li class="<?php echo $active_menu === 'profile' ? 'active' : ''; ?>">
                        <a href="/seller/profile">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>                    <li>
                        <form method="POST" action="/logout">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link text-decoration-none logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
             
            <div class="col-lg-10 main-content">
                <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo session('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo session('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/seller/products">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        <?php foreach ($breadcrumbs as $label => $url): ?>
                            <?php if ($url): ?>
                                <li class="breadcrumb-item">
                                    <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo $label; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
                
                <div class="content-wrapper">
                   
