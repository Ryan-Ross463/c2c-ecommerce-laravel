#!/bin/bash

echo "Starting C2C Ecommerce Laravel Application..."

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
for i in {1..30}; do
    if php artisan migrate:status > /dev/null 2>&1; then
        echo "MySQL is ready!"
        break
    fi
    echo "Waiting for MySQL... attempt $i/30"
    sleep 2
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
