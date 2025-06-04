# C2C E-commerce Railway Deployment Script (Windows)

Write-Host "🚀 Preparing C2C E-commerce for Railway deployment..." -ForegroundColor Green

# Step 1: Clean up local development files
Write-Host "📁 Cleaning up local development files..." -ForegroundColor Yellow
if (Test-Path "node_modules") { Remove-Item -Recurse -Force "node_modules" }
if (Test-Path "vendor") { Remove-Item -Recurse -Force "vendor" }
if (Test-Path "storage/logs") { Get-ChildItem "storage/logs" | Remove-Item -Force }
if (Test-Path "bootstrap/cache") { Get-ChildItem "bootstrap/cache" | Remove-Item -Force }

# Step 2: Install production dependencies
Write-Host "📦 Installing production dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction
npm ci

# Step 3: Build assets
Write-Host "🔨 Building production assets..." -ForegroundColor Yellow
npm run build

# Step 4: Clear and cache configurations
Write-Host "⚡ Optimizing Laravel for production..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 5: Generate key if not exists
if ((Get-Content ".env.production" | Select-String "APP_KEY=$").Count -gt 0) {
    Write-Host "🔑 Generating application key..." -ForegroundColor Yellow
    php artisan key:generate --env=production --force
}

Write-Host "✅ Project prepared for Railway deployment!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Push your code to GitHub" -ForegroundColor White
Write-Host "2. Connect your GitHub repo to Railway" -ForegroundColor White
Write-Host "3. Add MySQL database service" -ForegroundColor White
Write-Host "4. Configure environment variables" -ForegroundColor White
Write-Host "5. Deploy!" -ForegroundColor White
Write-Host ""
Write-Host "📖 See RAILWAY_DEPLOYMENT.md for detailed instructions" -ForegroundColor Cyan
