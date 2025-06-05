<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>CSS Loading Test</h1>";

// Test 1: Direct file access
$cssPath = __DIR__ . '/public/assets/css/admin_dashboard_header.css';
echo "<h2>Test 1: File Exists Check</h2>";
echo "CSS file path: " . $cssPath . "<br>";
echo "File exists: " . (file_exists($cssPath) ? "YES" : "NO") . "<br>";
echo "File readable: " . (is_readable($cssPath) ? "YES" : "NO") . "<br>";
echo "File size: " . (file_exists($cssPath) ? filesize($cssPath) . " bytes" : "N/A") . "<br>";

// Test 2: URL generation
echo "<h2>Test 2: URL Generation</h2>";
if (!function_exists('asset')) {
    function asset($path) {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
            return $baseUrl . '/' . ltrim($path, '/');
        } else {
            $baseUrl = 'http://localhost';
            return $baseUrl . '/public/' . ltrim($path, '/');
        }
    }
}

$cssUrl = asset('assets/css/admin_dashboard_header.css');
echo "Generated CSS URL: <a href='" . $cssUrl . "' target='_blank'>" . $cssUrl . "</a><br>";

// Test 3: HTTP request test
echo "<h2>Test 3: HTTP Request Test</h2>";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($cssUrl, false, $context);
$headers = $http_response_header ?? [];

echo "HTTP Response Headers:<br>";
foreach ($headers as $header) {
    echo htmlspecialchars($header) . "<br>";
}

echo "<br>Response received: " . ($response !== false ? "YES" : "NO") . "<br>";
echo "Response length: " . (is_string($response) ? strlen($response) . " bytes" : "N/A") . "<br>";

if ($response !== false) {
    echo "First 200 characters:<br>";
    echo "<pre>" . htmlspecialchars(substr($response, 0, 200)) . "</pre>";
}

// Test 4: CSS in HTML test
echo "<h2>Test 4: CSS Loading in HTML</h2>";
echo "<p>Testing if CSS loads properly in HTML context:</p>";
echo '<link rel="stylesheet" href="' . $cssUrl . '">';
echo '<style>
    .test-css-loading {
        color: red;
        font-size: 20px;
        font-weight: bold;
    }
    .test-external-css {
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        margin: 10px 0;
    }
</style>';

echo '<div class="test-css-loading">This should be red and bold (inline CSS)</div>';
echo '<div class="test-external-css admin-header">This should have admin header styling if external CSS loads</div>';

// Test 5: Browser check
echo "<h2>Test 5: Browser Information</h2>";
echo "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "<br>";
echo "Accept: " . ($_SERVER['HTTP_ACCEPT'] ?? 'Unknown') . "<br>";
echo "Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "<br>";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? 'YES' : 'NO') . "<br>";

?>
