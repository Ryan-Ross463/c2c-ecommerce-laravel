<?php
// Quick test to check URL generation
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Force the app URL
$_ENV['APP_URL'] = 'https://c2c-ecommerce-laravel-production-7647.up.railway.app';
config(['app.url' => 'https://c2c-ecommerce-laravel-production-7647.up.railway.app']);

// Include helpers
require_once __DIR__ . '/app/helpers.php';

echo "Testing URL generation:\n";
echo "my_url('/') = " . my_url('/') . "\n";
echo "my_url('/assets/css/main.css') = " . my_url('/assets/css/main.css') . "\n";
echo "asset('/assets/css/main.css') = " . asset('/assets/css/main.css') . "\n";
echo "config('app.url') = " . config('app.url') . "\n";
?>
