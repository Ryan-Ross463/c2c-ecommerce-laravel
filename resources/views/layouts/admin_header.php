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
    <meta name="base-url" content="<?php echo $base_url; ?>">
    <title><?php echo isset($page_title) ? $page_title . " - C2C E-commerce Admin" : "Admin Dashboard - C2C E-commerce"; ?></title><link rel="icon" href="<?php echo $base_url; ?>/assets/images/favicon.ico" type="image/x-icon">     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
      <!-- CRITICAL INLINE CSS - HIGHEST SPECIFICITY -->
    <style>
        /* Admin Body Styling */
        body.admin-body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            background-color: #f8f9fa !important;
            color: #333 !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Admin Header Styling */
        .admin-header {
            background-color: #2c3e50 !important;
            background: #2c3e50 !important;
            color: #fff !important;
            height: 60px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
        }
        
        .admin-brand {
            color: #fff !important;
            padding: 0 20px !important;
            font-size: 1.3rem !important;
            font-weight: 700 !important;
        }
        
        .admin-brand a {
            color: #fff !important;
            text-decoration: none !important;
        }
        
        .admin-brand a:hover {
            color: #3498db !important;
            text-decoration: none !important;
        }

        /* Mobile Menu Toggle */
        .admin-menu-toggle {
            display: none !important;
            background: none !important;
            border: none !important;
            color: #fff !important;
            font-size: 1.2rem !important;
            cursor: pointer !important;
            margin-right: 15px !important;
        }
        
        /* Navigation Styling */
        .admin-nav {
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            position: relative !important;
        }
        
        .admin-nav > a {
            color: #ecf0f1 !important;
            text-decoration: none !important;
            padding: 0 15px !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            transition: background-color 0.3s ease !important;
        }
        
        .admin-nav > a:hover,
        .admin-nav > a.active {
            background-color: #34495e !important;
            color: #fff !important;
            text-decoration: none !important;
        }

        /* Dropdown Styling */
        .admin-dropdown {
            position: relative !important;
            height: 100% !important;
        }

        .admin-dropdown-toggle {
            color: #ecf0f1 !important;
            text-decoration: none !important;
            padding: 0 15px !important;
            height: 60px !important;
            display: flex !important;
            align-items: center !important;
            cursor: pointer !important;
            transition: background-color 0.3s ease !important;
            border: none !important;
            background: none !important;
        }

        .admin-dropdown-toggle:hover {
            background-color: #34495e !important;
            color: #fff !important;
            text-decoration: none !important;
        }

        .admin-dropdown-toggle span {
            margin-left: 8px !important;
        }

        .admin-dropdown-toggle i {
            margin-right: 5px !important;
        }

        /* Dropdown Container */
        .admin-dropdown-container {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background: #fff !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
            min-width: 200px !important;
            z-index: 9999 !important;
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
            padding: 8px 0 !important;
        }

        .admin-dropdown-content a {
            display: block !important;
            padding: 10px 16px !important;
            color: #333 !important;
            text-decoration: none !important;
            transition: background-color 0.2s ease !important;
            border: none !important;
            width: 100% !important;
            text-align: left !important;
        }

        .admin-dropdown-content a:hover {
            background-color: #f8f9fa !important;
            color: #2c3e50 !important;
            text-decoration: none !important;
        }

        .admin-dropdown-content a i {
            margin-right: 8px !important;
            width: 16px !important;
            text-align: center !important;
        }

        .admin-dropdown-divider {
            height: 1px !important;
            background-color: #eee !important;
            margin: 8px 0 !important;
        }

        /* Main Content Area */
        .admin-main-content {
            flex: 1 !important;
            padding: 20px !important;
            background-color: #f8f9fa !important;
        }

        /* Admin Footer Styling */
        .admin-footer {
            background-color: #34495e !important;
            color: #ecf0f1 !important;
            padding: 20px 0 !important;
            margin-top: auto !important;
            border-top: 1px solid #2c3e50 !important;
        }

        .admin-footer-content {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
        }

        .admin-footer-left {
            flex: 1 !important;
        }

        .admin-footer-right {
            display: flex !important;
            gap: 20px !important;
        }

        .admin-footer a {
            color: #bdc3c7 !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }

        .admin-footer a:hover {
            color: #3498db !important;
            text-decoration: none !important;
        }

        .admin-footer-copy {
            font-size: 0.9rem !important;
            color: #95a5a6 !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-menu-toggle {
                display: block !important;
            }

            .admin-nav {
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                background-color: #2c3e50 !important;
                flex-direction: column !important;
                height: auto !important;
                display: none !important;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
            }

            .admin-nav.show {
                display: flex !important;
            }

            .admin-nav > a,
            .admin-dropdown-toggle {
                height: 50px !important;
                border-bottom: 1px solid #34495e !important;
            }

            .admin-dropdown-container {
                position: static !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                background-color: #34495e !important;
            }

            .admin-dropdown-content a {
                color: #ecf0f1 !important;
                padding-left: 30px !important;
            }

            .admin-dropdown-content a:hover {
                background-color: #2c3e50 !important;
                color: #fff !important;
            }

            .admin-footer-content {
                flex-direction: column !important;
                text-align: center !important;
                gap: 10px !important;
            }
        }
    </style>
      <?php if ($current_page === 'dashboard' || $current_folder === 'dashboard'): ?>
   
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin_dashboard.css'); ?>">
    <?php endif; ?>
    
    <?php if (isset($custom_css)) echo $custom_css; ?> <script>
        document.addEventListener('DOMContentLoaded', function() {
           
            const menuToggle = document.getElementById('adminMenuToggle');
            const adminNav = document.getElementById('adminNav');
            
            if (menuToggle && adminNav) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    adminNav.classList.toggle('show');
                });
            }
            
            const dropdownToggles = document.querySelectorAll('.admin-dropdown-toggle');
            
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const parentDropdown = this.closest('.admin-dropdown');
                    
                    document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                        if (dropdown !== parentDropdown) {
                            dropdown.classList.remove('active');
                            const container = dropdown.querySelector('.admin-dropdown-container');
                            if (container) {
                                container.style.zIndex = '';
                            }
                        }
                    });
                    
                    parentDropdown.classList.toggle('active');
                    
                    const container = parentDropdown.querySelector('.admin-dropdown-container');
                    if (container) {
                        if (parentDropdown.classList.contains('active')) {
                            container.style.zIndex = '10000';
                            container.style.position = 'absolute';
                        } else {
                            container.style.zIndex = '';
                        }
                    }
                });
            });
            
            document.addEventListener('click', function() {
                document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                    dropdown.classList.remove('active');
                });
            });
            
            document.querySelectorAll('.admin-dropdown-container').forEach(function(container) {
                container.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</head>
<body class="admin-body">    <header class="admin-header">
        <div class="admin-brand">
            <a href="<?php echo my_url('/admin'); ?>">C2C E-commerce Admin</a>
        </div>
        <button class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <nav class="admin-nav" id="adminNav">
            <a href="<?php echo my_url('/admin'); ?>" <?php echo request()->is('admin') ? 'class="active"' : ''; ?>>
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-users"></i> <span>Users</span>
                </a>
                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="<?php echo my_url('/admin/users/manage_users'); ?>">
                            <i class="fas fa-user-cog"></i> Manage Users
                        </a>
                        <a href="<?php echo my_url('/admin/users/create_admin'); ?>">
                            <i class="fas fa-user-plus"></i> Create Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-shopping-bag"></i> <span>Products</span>
                </a>
                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="<?php echo my_url('/admin/products'); ?>">
                            <i class="fas fa-list"></i> All Products
                        </a>
                        <a href="<?php echo my_url('/admin/products/create'); ?>">
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
                        <a href="<?php echo my_url('/admin/reports'); ?>">
                            <i class="fas fa-shopping-cart"></i> Orders Report
                        </a>
                        <a href="<?php echo my_url('/admin/reports/sales'); ?>">
                            <i class="fas fa-dollar-sign"></i> Sales Report
                        </a>
                        <a href="<?php echo my_url('/admin/reports/user_activity'); ?>">
                            <i class="fas fa-user-clock"></i> User Activity
                        </a>
                    </div>
                </div>
            </div>
            <a href="<?php echo my_url('/'); ?>" target="_blank">
                <i class="fas fa-globe"></i> View Site
            </a>
            <div class="admin-dropdown">
                <a href="#" class="admin-dropdown-toggle">
                    <i class="fas fa-user-shield"></i> <span><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin'; ?></span>
                </a>                <div class="admin-dropdown-container">
                    <div class="admin-dropdown-content">
                        <a href="<?php echo my_url('/admin/profile'); ?>">
                            <i class="fas fa-id-card"></i> My Profile
                        </a>
                        <div class="admin-dropdown-divider"></div>
                        <a href="<?php echo my_url('/logout'); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="<?php echo my_url('/logout'); ?>" method="POST" style="display: none;">
                            <?php 
                            if (function_exists('csrf_field')) {
                                echo csrf_field(); 
                            } else {
                                
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <script>       
        (function() {
           
            var allToggles = document.querySelectorAll('.admin-dropdown-toggle');
            
            for (var i = 0; i < allToggles.length; i++) {
                allToggles[i].onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var parentDropdown = this.closest('.admin-dropdown');
                    var isActive = parentDropdown.classList.contains('active');
                    
                    var allDropdowns = document.querySelectorAll('.admin-dropdown');
                    for (var j = 0; j < allDropdowns.length; j++) {
                        allDropdowns[j].classList.remove('active');
                        var container = allDropdowns[j].querySelector('.admin-dropdown-container');
                        if (container) {
                            container.style.zIndex = '';
                        }
                    }
                    
                    if (!isActive) {
                        parentDropdown.classList.add('active');
                        var container = parentDropdown.querySelector('.admin-dropdown-container');
                        if (container) {
                            container.style.zIndex = '10000';
                            container.style.position = 'absolute';
                        }
                    }
                    
                    return false;
                };
            }
           
            document.onclick = function(e) {
                if (!e.target.closest('.admin-dropdown')) {
                    var allDropdowns = document.querySelectorAll('.admin-dropdown');
                    for (var j = 0; j < allDropdowns.length; j++) {
                        allDropdowns[j].classList.remove('active');
                    }
                }
            };
            
            var allContainers = document.querySelectorAll('.admin-dropdown-container');
            for (var k = 0; k < allContainers.length; k++) {
                allContainers[k].onclick = function(e) {
                    e.stopPropagation();
                };
            }
        })();
    </script>
    <div class="admin-main-content">
