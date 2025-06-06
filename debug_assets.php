<?php
// Debug script to test asset serving
echo "<h1>Asset Debug Script</h1>";

// Test if files exist
$testFiles = [
    'public/assets/js/admin_header.js',
    'public/assets/css/admin_dashboard.css'
];

foreach ($testFiles as $file) {
    echo "<h2>Testing: $file</h2>";
    echo "File exists: " . (file_exists($file) ? "YES" : "NO") . "<br>";
    echo "File size: " . (file_exists($file) ? filesize($file) . " bytes" : "N/A") . "<br>";
    echo "Full path: " . realpath($file) . "<br><br>";
}

// Test asset function
echo "<h2>Asset Function Test</h2>";
if (!function_exists('asset')) {
    function asset($path) {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
            return $baseUrl . '/' . ltrim($path, '/');
        } else {
            $baseUrl = 'http://localhost';
            $publicPath = 'public/' . ltrim($path, '/');
            return $baseUrl . '/' . $publicPath;
        }
    }
}

echo "Generated JS URL: " . asset('assets/js/admin_header.js') . "<br>";
echo "Generated CSS URL: " . asset('assets/css/admin_dashboard.css') . "<br>";

// Test direct file serving
echo "<h2>Direct File Test</h2>";
echo '<script src="' . asset('assets/js/admin_header.js') . '"></script>';
echo '<link rel="stylesheet" href="' . asset('assets/css/admin_dashboard.css') . '">';

echo "<h2>Server Info</h2>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "<br>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "<br>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "<br>";
echo "Current working directory: " . getcwd() . "<br>";
?>
