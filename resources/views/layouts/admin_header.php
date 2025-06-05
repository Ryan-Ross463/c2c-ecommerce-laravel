<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('my_url')) {
    function my_url($path = '') {
        // For Railway deployment, ensure proper HTTPS URL
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            // Ensure we have a protocol
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
        // For Railway deployment, ensure proper HTTPS URL
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
            // Railway serves assets directly from root, no 'public/' prefix needed
            return $baseUrl . '/' . ltrim($path, '/');
        } else {
            $baseUrl = config('app.url', 'http://localhost');
            // Ensure we have a protocol
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
            // For local development, prepend 'public/' for the correct path
            $publicPath = 'public/' . ltrim($path, '/');
            return $baseUrl . '/' . $publicPath;
        }
    }
}

if (!isset($admin)) {
    $admin = (object) ['name' => 'Admin'];
}

$current_page = basename(request()->path());
$current_folder = basename(dirname(request()->path() == '/' ? '.' : request()->path()));
?>
<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo $base_url; ?>">
    <title><?php echo isset($page_title) ? $page_title . " - C2C E-commerce Admin" : "Admin Dashboard - C2C E-commerce"; ?></title><link rel="icon" href="<?php echo $base_url; ?>/assets/images/favicon.ico" type="image/x-icon">    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">      <!-- Admin Header and Footer styling -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin_dashboard_header.css'); ?>">
    
    <?php if ($current_page === 'dashboard' || $current_folder === 'dashboard'): ?>
    <!-- Dashboard specific styles -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin_dashboard.css'); ?>">
    <?php endif; ?>
      <!-- Comprehensive CSS Override for Railway deployment -->
    <style>
        /* Reset Bootstrap interference and establish admin styles */
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
        
        /* Override Bootstrap header/navbar styles completely */
        body.admin-body .admin-header,
        body.admin-body header.admin-header,
        .admin-body .navbar,
        .admin-body .navbar-brand {
            background-color: #2c3e50 !important;
            background-image: none !important;
            background: #2c3e50 !important;
            color: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            height: 60px !important;
            min-height: 60px !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 9998 !important;
            border-radius: 0 !important;
            border: none !important;
            width: 100% !important;
        }
        
        /* Brand/Logo styling */
        body.admin-body .admin-brand,
        body.admin-body .navbar-brand {
            padding: 0 20px !important;
            font-size: 1.3rem !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #fff !important;
            margin: 0 !important;
        }
        
        body.admin-body .admin-brand a,
        body.admin-body .navbar-brand a {
            color: #fff !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }
        
        body.admin-body .admin-brand a:hover,
        body.admin-body .navbar-brand a:hover {
            color: #3498db !important;
            text-shadow: 0 1px 3px rgba(52, 152, 219, 0.3) !important;
            text-decoration: none !important;
        }
        
        /* Navigation styling */
        body.admin-body .admin-nav,
        body.admin-body .navbar-nav {
            display: flex !important;
            align-items: center !important;
            gap: 0 !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        
        body.admin-body .admin-nav a,
        body.admin-body .admin-dropdown-toggle,
        body.admin-body .navbar-nav a,
        body.admin-body .nav-link {
            color: #fff !important;
            text-decoration: none !important;
            padding: 18px 16px !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            height: 60px !important;
            box-sizing: border-box !important;
            background: none !important;
            background-color: transparent !important;
            border: none !important;
            cursor: pointer !important;
            font-size: 0.9rem !important;
            margin: 0 !important;
        }
        
        body.admin-body .admin-nav a:hover,
        body.admin-body .admin-dropdown-toggle:hover,
        body.admin-body .admin-nav a.active,
        body.admin-body .navbar-nav a:hover,
        body.admin-body .nav-link:hover {
            background-color: #34495e !important;
            background: #34495e !important;
            color: #3498db !important;
            text-decoration: none !important;
        }
        
        /* Dropdown styling */
        body.admin-body .admin-dropdown {
            position: relative !important;
            height: 100% !important;
        }
        
        body.admin-body .admin-dropdown-container {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background-color: #fff !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
            min-width: 200px !important;
            z-index: 10000 !important;
            display: none !important;
        }
        
        body.admin-body .admin-dropdown.active .admin-dropdown-container {
            display: block !important;
        }
        
        body.admin-body .admin-dropdown-content {
            padding: 8px 0 !important;
        }
        
        body.admin-body .admin-dropdown-content a {
            color: #333 !important;
            padding: 10px 16px !important;
            display: block !important;
            text-decoration: none !important;
            transition: background-color 0.2s ease !important;
            height: auto !important;
        }
        
        body.admin-body .admin-dropdown-content a:hover {
            background-color: #f8f9fa !important;
            color: #007bff !important;
        }
        
        body.admin-body .admin-dropdown-divider {
            height: 1px !important;
            background-color: #e9ecef !important;
            margin: 8px 0 !important;
        }
        
        /* Main content and footer */
        body.admin-body .admin-main-content {
            flex: 1 !important;
            padding: 20px !important;
            background-color: #f8f9fa !important;
        }
        
        body.admin-body .admin-footer {
            background-color: #2c3e50 !important;
            color: #fff !important;
            text-align: center !important;
            padding: 15px 0 !important;
            margin-top: auto !important;
        }
        
        /* Mobile menu toggle */
        body.admin-body .admin-menu-toggle {
            display: none !important;
            background: none !important;
            border: none !important;
            color: #fff !important;
            font-size: 1.2rem !important;
            padding: 10px !important;
            cursor: pointer !important;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            body.admin-body .admin-menu-toggle {
                display: block !important;
            }
            
            body.admin-body .admin-nav,
            body.admin-body .navbar-nav {
                display: none !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                background-color: #2c3e50 !important;
                background: #2c3e50 !important;
                flex-direction: column !important;
                height: auto !important;
            }
            
            body.admin-body .admin-nav.show,
            body.admin-body .navbar-nav.show {
                display: flex !important;
            }
            
            body.admin-body .admin-nav a,
            body.admin-body .admin-dropdown-toggle,
            body.admin-body .navbar-nav a {
                height: auto !important;
                padding: 15px 20px !important;
                border-bottom: 1px solid #34495e !important;
            }
            
            body.admin-body .admin-dropdown-container {
                position: static !important;
                box-shadow: none !important;
                border: none !important;
                background-color: #34495e !important;
            }
            
            body.admin-body .admin-dropdown-content a {
                color: #fff !important;
                padding-left: 40px !important;
            }
        }
        
        /* Additional Bootstrap overrides */
        body.admin-body .navbar-toggler {
            border: none !important;
            padding: 0 !important;
        }
        
        body.admin-body .navbar-collapse {
            background-color: #2c3e50 !important;
        }
        
        /* Ensure icons are visible */
        body.admin-body i.fas,
        body.admin-body i.fa {
            color: inherit !important;
        }
    </style>
    
    <?php if (isset($custom_css)) echo $custom_css; ?><!-- Admin JS - Bootstrap toggle implementation for mobile menu -->    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple mobile menu toggle
            const menuToggle = document.getElementById('adminMenuToggle');
            const adminNav = document.getElementById('adminNav');
            
            if (menuToggle && adminNav) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    adminNav.classList.toggle('show');
                });
            }
              // Very simple dropdown toggle implementation
            const dropdownToggles = document.querySelectorAll('.admin-dropdown-toggle');
            
            // Add click event to each dropdown toggle
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Get the parent dropdown
                    const parentDropdown = this.closest('.admin-dropdown');
                    
                    // Close other dropdowns
                    document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                        if (dropdown !== parentDropdown) {
                            dropdown.classList.remove('active');
                            const container = dropdown.querySelector('.admin-dropdown-container');
                            if (container) {
                                container.style.zIndex = '';
                            }
                        }
                    });
                    
                    // Toggle this dropdown
                    parentDropdown.classList.toggle('active');
                    
                    // Force z-index for active dropdown
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
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.admin-dropdown').forEach(function(dropdown) {
                    dropdown.classList.remove('active');
                });
            });
            
            // Prevent dropdown menus from closing when clicking inside them
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
                                // Fallback for direct PHP includes                                echo '<input type="hidden" name="_token" value="'.($_SESSION['_token'] ?? md5(uniqid(mt_rand(), true))).'">';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Backup JavaScript for dropdown functionality - will run even if other scripts fail -->
    <script>        // This will run immediately
        (function() {
            // Direct implementation that bypasses potential issues
            var allToggles = document.querySelectorAll('.admin-dropdown-toggle');
            
            for (var i = 0; i < allToggles.length; i++) {
                allToggles[i].onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var parentDropdown = this.closest('.admin-dropdown');
                    var isActive = parentDropdown.classList.contains('active');
                    
                    // Close all dropdowns
                    var allDropdowns = document.querySelectorAll('.admin-dropdown');
                    for (var j = 0; j < allDropdowns.length; j++) {
                        allDropdowns[j].classList.remove('active');
                        var container = allDropdowns[j].querySelector('.admin-dropdown-container');
                        if (container) {
                            container.style.zIndex = '';
                        }
                    }
                    
                    // Toggle current dropdown
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
            
            // Close dropdowns when clicking outside
            document.onclick = function(e) {
                if (!e.target.closest('.admin-dropdown')) {
                    var allDropdowns = document.querySelectorAll('.admin-dropdown');
                    for (var j = 0; j < allDropdowns.length; j++) {
                        allDropdowns[j].classList.remove('active');
                    }
                }
            };
            
            // Prevent dropdown containers from closing when clicking inside
            var allContainers = document.querySelectorAll('.admin-dropdown-container');
            for (var k = 0; k < allContainers.length; k++) {
                allContainers[k].onclick = function(e) {
                    e.stopPropagation();
                };
            }
        })();
    </script>
    <div class="admin-main-content">
