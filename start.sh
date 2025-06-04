#!/bin/bash

echo "Starting C2C Ecommerce Laravel Application..."

# Debug: Show environment variables
echo "DEBUG: Environment variables:"
echo "DATABASE_URL: $DATABASE_URL"
echo "MYSQL_HOST: $MYSQL_HOST"
echo "MYSQL_PORT: $MYSQL_PORT"
echo "MYSQL_DATABASE: $MYSQL_DATABASE"
echo "MYSQL_USER: $MYSQL_USER"
echo "APP_URL: $APP_URL"

# Show .env file content
echo "DEBUG: .env file content:"
cat .env

# Test basic Laravel setup
echo "Testing Laravel configuration..."
php artisan --version

# Test database connection without migrations first
echo "Testing database connection..."
for i in {1..30}; do
    if php -r "
        require 'vendor/autoload.php';
        \$app = require_once 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            DB::connection()->getPdo();
            echo 'Database connected successfully!';
            exit(0);
        } catch (Exception \$e) {
            echo 'Database connection failed: ' . \$e->getMessage();
            exit(1);
        }
    " 2>/dev/null; then
        echo "Database connection established!"
        break
    fi
    echo "Waiting for database... attempt $i/30"
    sleep 3
done

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Start the Laravel server
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=$PORT
