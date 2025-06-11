<?php
echo "PHP script starting...\n";

// Test the my_url helper function
require_once 'app/helpers.php';

echo "Helper loaded...\n";

// Simulate local environment
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['HTTPS'] = null;

echo "Testing my_url() helper function:\n";

if (function_exists('my_url')) {
    echo "my_url function exists\n";
    echo "1. Root URL: " . my_url() . "\n";
    echo "2. Seller products: " . my_url('/seller/products') . "\n";
    echo "3. Store endpoint: " . my_url('/seller/products/store') . "\n";
    echo "4. With leading slash: " . my_url('seller/products/store') . "\n";
} else {
    echo "my_url function does not exist!\n";
}

// Test what HTTP_HOST is set to
echo "\nHTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'not set') . "\n";

echo "Script finished.\n";
?>
