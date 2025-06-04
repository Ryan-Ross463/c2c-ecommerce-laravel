#!/bin/bash

# C2C E-commerce Railway Deployment Script

echo "🚀 Preparing C2C E-commerce for Railway deployment..."

# Step 1: Clean up local development files
echo "📁 Cleaning up local development files..."
rm -rf node_modules
rm -rf vendor
rm -rf storage/logs/*
rm -rf bootstrap/cache/*

# Step 2: Install production dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci

# Step 3: Build assets
echo "🔨 Building production assets..."
npm run build

# Step 4: Clear and cache configurations
echo "⚡ Optimizing Laravel for production..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 5: Set proper permissions
echo "🔒 Setting proper file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Step 6: Generate key if not exists
if grep -q "APP_KEY=$" .env.production; then
    echo "🔑 Generating application key..."
    php artisan key:generate --env=production --force
fi

echo "✅ Project prepared for Railway deployment!"
echo ""
echo "Next steps:"
echo "1. Push your code to GitHub"
echo "2. Connect your GitHub repo to Railway"
echo "3. Add MySQL database service"
echo "4. Configure environment variables"
echo "5. Deploy!"
echo ""
echo "📖 See RAILWAY_DEPLOYMENT.md for detailed instructions"
