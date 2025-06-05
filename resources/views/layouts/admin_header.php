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

// Safe way to get current page without Laravel helpers
$current_page = '';
$current_folder = '';

try {
    if (function_exists('request') && request()) {
        $current_page = basename(request()->path());
        $current_folder = basename(dirname(request()->path() == '/' ? '.' : request()->path()));
    } else {
        // Fallback using $_SERVER
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH) ?? '/';
        $current_page = basename($path);
        $current_folder = basename(dirname($path == '/' ? '.' : $path));
    }
} catch (Exception $e) {
    // Fallback for any errors
    $current_page = '';
    $current_folder = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo $base_url; ?>">
    <title><?php echo isset($page_title) ? $page_title . " - C2C E-commerce Admin" : "Admin Dashboard - C2C E-commerce"; ?></title><link rel="icon" href="<?php echo $base_url; ?>/assets/images/favicon.ico" type="image/x-icon">    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">    <!-- Admin Header and Footer styling -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin_dashboard_header.css'); ?>?v=<?php echo time(); ?>">
      <?php if ($current_page === 'dashboard' || $current_folder === 'dashboard'): ?>
    <!-- Dashboard specific styles -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin_dashboard.css'); ?>">
    <?php endif; ?>
    
    <!-- Note: Admin header and footer styling is now handled by external CSS file -->
    <!-- admin_dashboard_header.css contains all necessary styling with proper Bootstrap overrides -->
    
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
                    
                    // Close all dropdowns
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
