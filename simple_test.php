<?php
echo "PHP Server Test - " . date('Y-m-d H:i:s') . "\n";
echo "Environment: " . (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false ? 'Railway' : 'Local') . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n";

// Test database connection
try {
    $host = getenv('MYSQLHOST');
    $port = getenv('MYSQLPORT');
    $database = getenv('MYSQLDATABASE');
    $username = getenv('MYSQLUSER');
    $password = getenv('MYSQLPASSWORD');
    
    if ($host && $port && $database && $username && $password) {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        echo "Database connection: SUCCESS\n";
    } else {
        echo "Database connection: MISSING ENV VARS\n";
    }
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

echo "Test completed successfully!";
?>
