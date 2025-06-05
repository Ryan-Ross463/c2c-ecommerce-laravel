<?php
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

$cleanUri = $uri;
if (strpos($cleanUri, '?') !== false) {
    $cleanUri = substr($cleanUri, 0, strpos($cleanUri, '?'));
}

if ($cleanUri !== '/') {
    $publicFile = __DIR__.'/public'.$cleanUri;
    if (file_exists($publicFile) && is_file($publicFile)) {
        $extension = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
        
        header('Cache-Control: public, max-age=3600');
        
        switch ($extension) {
            case 'css':
                header('Content-Type: text/css; charset=utf-8');
                break;
            case 'js':
                header('Content-Type: application/javascript; charset=utf-8');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                break;
            case 'woff':
            case 'woff2':
                header('Content-Type: font/woff');
                break;
            case 'ttf':
                header('Content-Type: font/ttf');
                break;
            case 'html':
                header('Content-Type: text/html; charset=utf-8');
                break;
            default:
            
                break;
        }
        
        header('Content-Length: ' . filesize($publicFile));
        
        readfile($publicFile);
        return;
    }
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/public/index.php';

if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
 
    $domain = $_SERVER['HTTP_HOST'] ?? '';
    if ($domain && strpos($requestUri, $domain) !== false) {
        $requestUri = str_replace('/' . $domain, '', $requestUri);
        $_SERVER['REQUEST_URI'] = $requestUri;
    }
}

if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
    $domain = $_SERVER['HTTP_HOST'];
    $_ENV['APP_URL'] = 'https://' . $domain;
    $_SERVER['APP_URL'] = 'https://' . $domain;
    putenv('APP_URL=https://' . $domain);
    
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['REQUEST_SCHEME'] = 'https';
}

require_once __DIR__.'/public/index.php';
