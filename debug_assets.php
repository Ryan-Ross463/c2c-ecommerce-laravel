<?php
// Debug asset URLs to identify the issue
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include helpers
require_once __DIR__ . '/app/helpers.php';

echo "<h2>Asset URL Debug Page</h2>";

echo "<h3>Environment Information:</h3>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "<br>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "<br>";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "<br>";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'Not set') . "<br>";
echo "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'Not set') . "<br>";

echo "<h3>Asset URL Tests:</h3>";

// Test different asset URL approaches
$testPaths = [
    'assets/css/admin_dashboard_header.css',
    'public/assets/css/admin_dashboard_header.css'
];

foreach ($testPaths as $path) {
    $url = asset($path);
    echo "asset('$path') = <a href='$url' target='_blank'>$url</a><br>";
    
    // Test if URL is accessible
    $headers = @get_headers($url);
    $status = $headers ? $headers[0] : 'Could not fetch headers';
    echo "Status: $status<br><br>";
}

echo "<h3>Direct CSS Test:</h3>";
$cssUrl = asset('assets/css/admin_dashboard_header.css');
echo "<link rel='stylesheet' href='$cssUrl'>";
echo "<style>body { background-color: #f0f0f0; padding: 20px; font-family: Arial, sans-serif; }</style>";

echo "<h3>CSS Content Test:</h3>";
$cssPath = __DIR__ . '/public/assets/css/admin_dashboard_header.css';
if (file_exists($cssPath)) {
    echo "CSS file exists at: $cssPath<br>";
    echo "File size: " . filesize($cssPath) . " bytes<br>";
    echo "First 200 characters of CSS:<br>";
    echo "<pre>" . htmlspecialchars(substr(file_get_contents($cssPath), 0, 200)) . "...</pre>";
} else {
    echo "CSS file NOT found at: $cssPath<br>";
}

echo "<h3>Alternative URL Tests:</h3>";

// Test direct URLs without helper function
$baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
$directUrls = [
    $baseUrl . '/assets/css/admin_dashboard_header.css',
    $baseUrl . '/public/assets/css/admin_dashboard_header.css',
    $baseUrl . '/C2C_ecommerce_laravel/public/assets/css/admin_dashboard_header.css'
];

foreach ($directUrls as $url) {
    echo "<a href='$url' target='_blank'>$url</a><br>";
    $headers = @get_headers($url);
    $status = $headers ? $headers[0] : 'Could not fetch headers';
    echo "Status: $status<br><br>";
}
?>
