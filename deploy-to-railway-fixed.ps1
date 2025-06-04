# C2C E-commerce Railway Deployment Script (Windows)
# Updated for Railway with URL fixes

Write-Host "🚀 Preparing C2C E-commerce for Railway deployment..." -ForegroundColor Green

# Step 1: Clean up local development files
Write-Host "📁 Cleaning up local development files..." -ForegroundColor Yellow
if (Test-Path "node_modules") { Remove-Item -Recurse -Force "node_modules" }
if (Test-Path "vendor") { Remove-Item -Recurse -Force "vendor" }
if (Test-Path "storage/logs") { Get-ChildItem "storage/logs" | Remove-Item -Force }
if (Test-Path "bootstrap/cache") { Get-ChildItem "bootstrap/cache" | Remove-Item -Force }

# Step 2: Copy Railway environment file
Write-Host "📄 Setting up Railway environment file..." -ForegroundColor Yellow
Copy-Item -Path ".env.railway" -Destination ".env" -Force

# Step 3: Install production dependencies
Write-Host "📦 Installing production dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction
npm ci

# Step 4: Build assets
Write-Host "🔨 Building production assets..." -ForegroundColor Yellow
npm run build

# Step 5: Clear and cache configurations
Write-Host "⚡ Optimizing Laravel for production..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Prepare SQLite database
Write-Host "🗄️ Setting up SQLite database for Railway..." -ForegroundColor Yellow
if (-not (Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Path "database/database.sqlite" -Force
}
php artisan migrate:fresh --seed --force

Write-Host "✅ Project prepared for Railway deployment!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Push your code to GitHub" -ForegroundColor White
Write-Host "2. Connect your GitHub repo to Railway" -ForegroundColor White
Write-Host "3. Deploy!" -ForegroundColor White
Write-Host ""
Write-Host "📖 See RAILWAY_DEPLOYMENT_FIX.md for detailed instructions" -ForegroundColor Cyan
