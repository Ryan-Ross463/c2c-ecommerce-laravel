<?php
// Simple PHP test script for Railway deployment debugging

echo "PHP Test Script Running...\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . getcwd() . "\n";

// Test basic Laravel bootstrap
try {
    echo "Testing Laravel bootstrap...\n";
    require_once __DIR__ . '/vendor/autoload.php';
    echo "Composer autoload: OK\n";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "Laravel app bootstrap: OK\n";
    
    // Test basic configuration
    echo "Testing configuration...\n";
    $config = $app->make('config');
    echo "App Name: " . $config->get('app.name', 'Not Set') . "\n";
    echo "App Environment: " . $config->get('app.env', 'Not Set') . "\n";
    echo "App URL: " . $config->get('app.url', 'Not Set') . "\n";
    
    echo "All tests passed!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
