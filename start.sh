set -e

echo "Starting C2C E-commerce Laravel application..."
echo "Current working directory: $(pwd)"
echo "PHP version: $(php --version | head -n1)"

PORT=${PORT:-8080}
echo "Using port: $PORT"

if [ ! -f "artisan" ]; then
    echo "ERROR: artisan file not found!"
    exit 1
fi

if [ ! -f "router.php" ]; then
    echo "ERROR: router.php file not found!"
    exit 1
fi

echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || echo "Permission setting failed, continuing..."

echo "Testing database connection..."
php -r "
try {
    \$pdo = new PDO('mysql:host=' . getenv('MYSQLHOST') . ';port=' . getenv('MYSQLPORT') . ';dbname=' . getenv('MYSQLDATABASE'), getenv('MYSQLUSER'), getenv('MYSQLPASSWORD'));
    echo 'Database connection successful\n';
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage() . '\n';
}
" || echo "Database connection test failed, continuing..."

echo "Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || echo "Config clear failed, continuing..."
php artisan cache:clear 2>/dev/null || echo "Cache clear failed, continuing..."
php artisan view:clear 2>/dev/null || echo "View clear failed, continuing..."

echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migration failed, continuing..."

echo "Testing artisan..."
php artisan --version || echo "Artisan test failed"

echo "Testing PHP syntax..."
php -l public/index.php || echo "index.php syntax error"
php -l router.php || echo "router.php syntax error"

echo "Starting PHP server on 0.0.0.0:$PORT..."
echo "Server will be accessible at http://0.0.0.0:$PORT"

exec php -S 0.0.0.0:$PORT -t public router.php
