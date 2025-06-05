<?php
// Asset verification script for Railway deployment
header('Content-Type: application/json');

$baseDir = __DIR__ . '/assets';
$results = [
    'css' => [],
    'js' => [],
    'images' => [],
    'server_info' => [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'app_url' => env('APP_URL', 'Not set'),
        'current_time' => date('Y-m-d H:i:s')
    ]
];

// Check CSS files
$cssDir = $baseDir . '/css';
if (is_dir($cssDir)) {
    $cssFiles = scandir($cssDir);
    foreach ($cssFiles as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
            $filePath = $cssDir . '/' . $file;
            $results['css'][] = [
                'file' => $file,
                'exists' => file_exists($filePath),
                'size' => file_exists($filePath) ? filesize($filePath) : 0,
                'readable' => is_readable($filePath),
                'url' => '/assets/css/' . $file,
                'modified' => file_exists($filePath) ? date('Y-m-d H:i:s', filemtime($filePath)) : null
            ];
        }
    }
}

// Check JS files  
$jsDir = $baseDir . '/js';
if (is_dir($jsDir)) {
    $jsFiles = scandir($jsDir);
    foreach ($jsFiles as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
            $filePath = $jsDir . '/' . $file;
            $results['js'][] = [
                'file' => $file,
                'exists' => file_exists($filePath),
                'size' => file_exists($filePath) ? filesize($filePath) : 0,
                'readable' => is_readable($filePath),
                'url' => '/assets/js/' . $file,
                'modified' => file_exists($filePath) ? date('Y-m-d H:i:s', filemtime($filePath)) : null
            ];
        }
    }
}

// Check images directory
$imgDir = $baseDir . '/images';
if (is_dir($imgDir)) {
    $imageFiles = scandir($imgDir);
    $imageCount = 0;
    foreach ($imageFiles as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
            $imageCount++;
        }
    }
    $results['images']['count'] = $imageCount;
    $results['images']['directory_exists'] = true;
} else {
    $results['images']['count'] = 0;
    $results['images']['directory_exists'] = false;
}

echo json_encode($results, JSON_PRETTY_PRINT);
?>
