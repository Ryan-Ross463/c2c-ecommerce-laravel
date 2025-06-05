<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['_token'])) {
    $_SESSION['_token'] = md5(uniqid(mt_rand(), true));
}

if (!isset($base_url)) {    $base_url = url('/');
    
    if (strpos($base_url, '/c2c_ecommerce/C2C_ecommerce_laravel') === false) {
        $base_url = env('APP_URL', 'http://localhost');
    }
}

if (!function_exists('my_url')) {
    function my_url($path = '') {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
        }
        return $baseUrl . ($path ? '/' . ltrim($path, '/') : '');
    }
}

$current_path = request()->path();
$current_page = basename($current_path == '/' ? 'index.php' : $current_path);
$current_folder = basename(dirname($current_path == '/' ? '.' : $current_path));
?>
<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['_token'] ?? ''; ?>">
    <title><?php echo isset($page_title) ? $page_title : 'C2C E-commerce'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    <link href="<?php echo my_url('/assets/css/main.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/index.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/header.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/footer.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/products.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/terms.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/pages.css').'?v='.time(); ?>" rel="stylesheet">
    <link href="<?php echo my_url('/assets/css/popup.css').'?v='.time(); ?>" rel="stylesheet">
    <?php if (isset($custom_css)) echo $custom_css; ?>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <script src="<?php echo my_url('/assets/js/header.js').'?v='.time(); ?>" defer></script>
</head>
<body>    <header id="main-header" class="navbar navbar-expand-lg shadow-sm sticky-top">
        <div class="container-fluid container-lg">
            <a href="<?php echo my_url('/'); ?>" class="navbar-brand fw-bold">
                <i class="fas fa-shopping-cart me-2"></i>C2C E-commerce
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
              <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="<?php echo my_url('/'); ?>" 
                           class="nav-link <?php echo ($current_page == '/' || $current_page == '') ? 'active' : ''; ?>">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-shopping-bag me-1"></i> Products
                        </a>                        <ul class="dropdown-menu">
                            <li>                                <a class="dropdown-item" href="<?php echo my_url('/products'); ?>">
                                    <i class="fas fa-list me-2"></i> Browse All
                                </a>
                            </li>                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->role_id == 3)) { ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo my_url('/seller/products/create'); ?>">
                                        <i class="fas fa-plus-circle me-2"></i> Sell a Product
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo my_url('/about'); ?>" 
                           class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle me-1"></i> About
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo my_url('/contact'); ?>" 
                           class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                </ul>                <div class="d-flex" id="header-actions">
                    <?php if (auth()->check()) { ?>
                        <div class="dropdown">                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?php echo auth()->user()->name; ?>
                            </a>                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <?php if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) { ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo my_url('/seller/products'); ?>">
                                        <i class="fas fa-store me-2"></i> Seller Dashboard
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (auth()->user()->role_id == 3) { ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo my_url('/admin'); ?>">
                                        <i class="fas fa-cog me-2"></i> Admin Dashboard
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) { ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo my_url('/seller/products/create'); ?>">
                                        <i class="fas fa-plus-circle me-2"></i> Sell Item
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a class="dropdown-item" href="<?php 
                                        if (auth()->user()->role_id == 3) {
                                            echo my_url('/admin/profile');
                                        } elseif (auth()->user()->role_id == 2) {
                                            echo my_url('/seller/profile');
                                        } else {
                                            echo my_url('/buyer/profile');
                                        }
                                    ?>">
                                        <i class="fas fa-user-circle me-2"></i> My Profile
                                    </a>
                                </li>                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form-header" method="POST" action="<?php echo my_url('/logout'); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div><?php } else { ?>
                        <a href="<?php echo my_url('/login'); ?>" 
                           class="btn btn-outline-primary me-2 <?php echo ($current_page == 'login') ? 'active' : ''; ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <?php if ($current_page !== 'terms') { ?>
                            <a href="<?php echo my_url('/register'); ?>" 
                               class="btn btn-primary <?php echo ($current_page == 'register') ? 'active' : ''; ?>">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>
    <main>
<div class="content-wrapper">
