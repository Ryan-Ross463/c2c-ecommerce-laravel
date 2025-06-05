#!/bin/bash

# Temporary debug startup script for Railway
set -e

echo "=== RAILWAY DEBUG MODE ==="
echo "Starting debug C2C E-commerce Laravel application..."
echo "Current working directory: $(pwd)"
echo "PHP version: $(php --version | head -n1)"

# Set default port if not provided
PORT=${PORT:-8080}
echo "Using port: $PORT"

# List directory contents
echo "Directory contents:"
ls -la

# Check if essential files exist
echo "Checking essential files..."
[ -f "artisan" ] && echo "✓ artisan found" || echo "✗ artisan missing"
[ -f "router.php" ] && echo "✓ router.php found" || echo "✗ router.php missing"
[ -f "simple_test.php" ] && echo "✓ simple_test.php found" || echo "✗ simple_test.php missing"
[ -f "public/index.php" ] && echo "✓ public/index.php found" || echo "✗ public/index.php missing"

# Test PHP basic functionality
echo "Testing PHP basic functionality..."
php -r "echo 'PHP basic test: OK\n';" || echo "PHP basic test: FAILED"

# Test simple PHP file
echo "Testing simple PHP file..."
php simple_test.php || echo "Simple PHP test: FAILED"

# Ensure storage directories exist and are writable
echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set proper permissions (if possible)
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || echo "Permission setting failed, continuing..."

# Test artisan availability
echo "Testing artisan..."
php artisan --version 2>/dev/null || echo "Artisan test failed, continuing..."

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || echo "Config clear failed, continuing..."
php artisan cache:clear 2>/dev/null || echo "Cache clear failed, continuing..."
php artisan view:clear 2>/dev/null || echo "View clear failed, continuing..."

# Test database connection
echo "Testing database connection..."
php -r "
try {
    \$host = getenv('MYSQLHOST');
    \$port = getenv('MYSQLPORT');
    \$database = getenv('MYSQLDATABASE');
    \$username = getenv('MYSQLUSER');
    \$password = getenv('MYSQLPASSWORD');
    
    if (\$host && \$port && \$database && \$username && \$password) {
        \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$database\", \$username, \$password, [PDO::ATTR_TIMEOUT => 5]);
        echo \"Database connection: SUCCESS\\n\";
    } else {
        echo \"Database connection: MISSING ENV VARS\\n\";
        echo \"Host: \$host, Port: \$port, DB: \$database, User: \$username\\n\";
    }
} catch (Exception \$e) {
    echo \"Database connection: FAILED - \" . \$e->getMessage() . \"\\n\";
}
" || echo "Database connection test script failed"

# Start PHP server in debug mode
echo "=== STARTING PHP SERVER ==="
echo "Starting PHP server on 0.0.0.0:$PORT..."
echo "Access the application at: http://0.0.0.0:$PORT"
echo "Simple test at: http://0.0.0.0:$PORT/simple_test.php"

# Use exec to replace the shell process with PHP server
# This ensures proper signal handling and keeps the container alive
exec php -S 0.0.0.0:$PORT -t . -d display_errors=1 -d log_errors=1
