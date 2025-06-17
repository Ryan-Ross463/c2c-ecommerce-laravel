<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

$base_url = my_url();

if (!function_exists('asset')) {
    function asset($path) {
       
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
          
            return $baseUrl . '/' . ltrim($path, '/');
        } else {
            $baseUrl = config('app.url', 'http://localhost');
           
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
          
            $publicPath = 'public/' . ltrim($path, '/');
            return $baseUrl . '/' . $publicPath;
        }
    }
}

if (!isset($admin)) {
    $admin = (object) ['name' => 'Admin'];
}

$current_page = '';
$current_folder = '';

try {
    if (function_exists('request') && request()) {
        $current_page = basename(request()->path());
        $current_folder = basename(dirname(request()->path() == '/' ? '.' : request()->path()));
    } else {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH) ?? '/';
        $current_page = basename($path);
        $current_folder = basename(dirname($path == '/' ? '.' : $path));
    }
} catch (Exception $e) {
    $current_page = '';
    $current_folder = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo my_url(); ?>">
    <title><?php echo isset($page_title) ? $page_title . " - C2C E-commerce Admin" : "Admin Dashboard - C2C E-commerce"; ?></title><link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/build/assets/app-CMxZnDx3.css">
    <script type="module" src="/build/assets/app-T1DpEqax.js"></script>
    
    <style>
        .admin-body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-header {
            background-color: #2c3e50 !important;
            color: white !important;
            padding: 0.75rem 1rem !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
            width: 100% !important;
            min-height: 60px !important;
        }

        .admin-brand a {
            color: white !important;
            text-decoration: none !important;
            font-size: 1.25rem !important;
            font-weight: bold !important;
        }

        .admin-brand a:hover {
            color: #ecf0f1 !important;
        }

        .admin-menu-toggle {
            display: none !important;
            background: none !important;
            border: none !important;
            color: white !important;
            font-size: 1.25rem !important;
            cursor: pointer !important;
            padding: 0.5rem !important;
        }

        .admin-nav {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        .admin-nav > a {
            color: white !important;
            text-decoration: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 4px !important;
            transition: background-color 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .admin-nav > a:hover {
            background-color: rgba(255,255,255,0.1) !important;
            color: white !important;
        }

        .admin-nav > a.active {
            background-color: rgba(255,255,255,0.2) !important;
        }

        .admin-dropdown {
            position: relative !important;
        }

        .admin-dropdown-toggle {
            color: white !important;
            text-decoration: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 4px !important;
            transition: background-color 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            cursor: pointer !important;
        }

        .admin-dropdown-toggle:hover {
            background-color: rgba(255,255,255,0.1) !important;
            color: white !important;
        }

        .admin-dropdown-container {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background: white !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
            min-width: 200px !important;
            z-index: 10000 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transform: translateY(-10px) !important;
            transition: all 0.3s ease !important;
        }

        .admin-dropdown.active .admin-dropdown-container {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        .admin-dropdown-content {
            padding: 0.5rem 0 !important;
        }

        .admin-dropdown-content a {
            display: block !important;
            padding: 0.75rem 1rem !important;
            color: #333 !important;
            text-decoration: none !important;
            transition: background-color 0.3s ease !important;
        }

        .admin-dropdown-content a:hover {
            background-color: #f8f9fa !important;
            color: #2c3e50 !important;
        }

        .admin-dropdown-divider {
            height: 1px !important;
            background-color: #eee !important;
            margin: 0.5rem 0 !important;
        }

        .admin-main-content {
            padding: 2rem 1rem !important;
            min-height: calc(100vh - 60px) !important;
        }

        @media (max-width: 992px) {
            .admin-header {
                flex-wrap: wrap !important;
                padding: 0.75rem 1rem !important;
            }

            .admin-menu-toggle {
                display: block !important;
                order: 2 !important;
            }

            .admin-brand {
                order: 1 !important;
                flex: 1 !important;
            }

            .admin-nav {
                display: none !important;
                width: 100% !important;
                flex-direction: column !important;
                background-color: #34495e !important;
                padding: 1rem 0 !important;
                margin-top: 0.75rem !important;
                border-radius: 4px !important;
                order: 3 !important;
                gap: 0 !important;
            }

            .admin-nav.show {
                display: flex !important;
                animation: slideDown 0.3s ease !important;
            }

            .admin-nav > a {
                width: 100% !important;
                padding: 0.75rem 1rem !important;
                border-radius: 0 !important;
                border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            }

            .admin-nav > a:last-child {
                border-bottom: none !important;
            }

            .admin-dropdown {
                width: 100% !important;
            }

            .admin-dropdown-toggle {
                width: 100% !important;
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid rgba(255,255,255,0.1) !important;
                justify-content: space-between !important;
            }

            .admin-dropdown-toggle::after {
                content: "▼" !important;
                font-size: 0.8rem !important;
                transition: transform 0.3s ease !important;
            }

            .admin-dropdown.active .admin-dropdown-toggle::after {
                transform: rotate(180deg) !important;
            }

            .admin-dropdown-container {
                position: static !important;
                background-color: #2c3e50 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
                margin-left: 0 !important;
                transform: none !important;
                opacity: 1 !important;
                visibility: visible !important;
                max-height: 0 !important;
                overflow: hidden !important;
                transition: max-height 0.3s ease !important;
            }

            .admin-dropdown.active .admin-dropdown-container {
                max-height: 300px !important;
            }

            .admin-dropdown-content a {
                padding: 0.75rem 2rem !important;
                color: #ecf0f1 !important;
                border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            }

            .admin-dropdown-content a:hover {
                background-color: rgba(255,255,255,0.1) !important;
                color: white !important;
            }

            .admin-dropdown-divider {
                background-color: rgba(255,255,255,0.1) !important;
                margin: 0 !important;
            }

            .admin-main-content {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            .admin-brand a {
                font-size: 1.1rem !important;
            }

            .admin-main-content {
                padding: 0.75rem !important;
            }

            .admin-dropdown-container {
                min-width: 100% !important;
            }

            .admin-nav > a {
                font-size: 0.9rem !important;
            }

            .admin-dropdown-content a {
                font-size: 0.9rem !important;
                padding: 0.5rem 1.5rem !important;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width: 993px) {
            .admin-dropdown-container {
                position: absolute !important;
            }
        }
    </style>      <?php if ($current_page === 'dashboard' || $current_folder === 'dashboard'): ?>
   
    <link rel="stylesheet" href="/assets/css/admin_dashboard.css">
    <?php endif; ?>
      <?php if (isset($custom_css)) echo $custom_css; ?>
      <script>
        console.log('Admin header loading...');
    </script>
    
    <script src="/assets/js/admin_header.js"></script>
</head>
<body class="admin-body">    <header class="admin-header">
        <div class="admin-brand">
            <a href="/admin">C2C E-commerce Admin</a>
        </div>
        <button class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <nav class="admin-nav" id="adminNav">
            <a href="/admin" <?php echo (isset($_GET['page']) && $_GET['page'] == 'dashboard') || basename($_SERVER['REQUEST_URI']) == 'admin' ? 'class="active"' : ''; ?>>
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-users"></i> <span>Users</span>
                </a>
                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="/admin/users/manage_users">
                            <i class="fas fa-user-cog"></i> Manage Users
                        </a>
                        <a href="/admin/users/create_admin">
                            <i class="fas fa-user-plus"></i> Create Admin
                        </a>
                    </div>
                </div>
            </div>            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-shopping-bag"></i> <span>Products</span>
                </a>
                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="/admin/products">
                            <i class="fas fa-list"></i> All Products
                        </a>
                        <a href="/admin/products/create">
                            <i class="fas fa-plus-circle"></i> Add Product
                        </a>
                    </div>
                </div>
            </div>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="/admin/reports">
                            <i class="fas fa-shopping-cart"></i> Orders Report
                        </a>
                        <a href="/admin/reports/sales">
                            <i class="fas fa-dollar-sign"></i> Sales Report
                        </a>
                        <a href="/admin/reports/user_activity">
                            <i class="fas fa-user-clock"></i> User Activity
                        </a>
                    </div>
                </div>
            </div>            <a href="/" target="_blank">
                <i class="fas fa-globe"></i> View Site
            </a>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-user-shield"></i> <span><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin'; ?></span>
                </a>                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="/admin/profile">
                            <i class="fas fa-id-card"></i> My Profile
                        </a>
                        <div class="admin-dropdown-divider"></div>
                        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                            <?php 
                            if (function_exists('csrf_field')) {
                                echo csrf_field(); 
                            } else {
                                
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>    </header>
    
    <div class="admin-main-content">
