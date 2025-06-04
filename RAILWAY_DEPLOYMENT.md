# C2C E-commerce Laravel - Railway Deployment Guide

## Prerequisites
1. Railway account (sign up at railway.app)
2. GitHub account
3. Your Laravel project pushed to GitHub

## Step 1: Prepare Your Laravel Project for Railway

### 1.1 Create Railway Configuration Files

Create `railway.json` in your project root:
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "nixpacks"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT",
    "healthcheckPath": "/",
    "healthcheckTimeout": 300
  }
}
```

### 1.2 Create Nixpacks Configuration

Create `nixpacks.toml` in your project root:
```toml
[phases.setup]
nixPkgs = ["nodejs", "npm", "php82", "php82Packages.composer"]

[phases.install]
cmds = [
    "composer install --no-dev --optimize-autoloader",
    "npm install",
    "npm run build"
]

[phases.build]
cmds = [
    "php artisan config:cache",
    "php artisan route:cache",
    "php artisan view:cache"
]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
```

### 1.3 Update Environment Configuration

Create `.env.example` with Railway-specific settings:
```env
APP_NAME="C2C E-commerce"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app.railway.app

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Step 2: Deploy to Railway

### 2.1 Connect Your GitHub Repository

1. Go to [railway.app](https://railway.app)
2. Sign in with GitHub
3. Click "New Project"
4. Select "Deploy from GitHub repo"
5. Choose your C2C e-commerce repository

### 2.2 Configure Environment Variables

In Railway dashboard, go to your project settings and add these environment variables:

**Required Variables:**
```
APP_KEY=base64:generate-this-with-php-artisan-key-generate
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.railway.app
```

**Database Variables** (Railway will auto-generate these when you add MySQL):
```
DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=auto-generated-password
```

### 2.3 Add MySQL Database

1. In Railway dashboard, click "New Service"
2. Select "Database" → "MySQL"
3. Railway will automatically set up database environment variables

### 2.4 Configure Domains

1. Go to "Settings" → "Domains"
2. Railway provides a free `.railway.app` subdomain
3. Optionally, add your custom domain

## Step 3: Database Migration and Setup

### 3.1 Run Migrations

Railway will automatically run migrations on deploy, but you can also run them manually:

1. Go to your project in Railway
2. Open the "Deployments" tab
3. Click on the latest deployment
4. Use the terminal to run:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 3.2 Generate Application Key

If not already set, generate the app key:
```bash
php artisan key:generate --force
```

## Step 4: File Storage Configuration

### 4.1 Configure Public Storage

Update `config/filesystems.php` for Railway:
```php
'default' => env('FILESYSTEM_DISK', 'public'),

'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### 4.2 Create Storage Link

Run this command in Railway terminal:
```bash
php artisan storage:link
```

## Step 5: Optimization for Production

### 5.1 Create Production Build Script

Add to `package.json`:
```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "production": "npm run build"
  }
}
```

### 5.2 Optimize Laravel for Production

Create `bootstrap/cache/config.php` optimization script:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Step 6: Custom Start Command

Update your start command in Railway:
```bash
php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

## Step 7: Environment-Specific Configurations

### 7.1 Update Asset URLs

In your Blade templates, ensure asset URLs work in production:
```php
<link rel="stylesheet" href="{{ asset('assets/css/admin_products.css') }}">
<script src="{{ asset('assets/js/admin_products.js') }}"></script>
```

### 7.2 Configure CSRF and Session

Update `config/session.php`:
```php
'domain' => env('SESSION_DOMAIN', '.railway.app'),
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

## Step 8: Testing and Debugging

### 8.1 Monitor Logs

Railway provides real-time logs:
1. Go to your project dashboard
2. Click "View Logs"
3. Monitor for any errors during deployment

### 8.2 Common Issues and Solutions

**Issue: 500 Internal Server Error**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Issue: Database Connection**
- Verify database environment variables
- Check Railway MySQL service is running

**Issue: File Permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Step 9: Final Checklist

- [ ] Repository pushed to GitHub
- [ ] Railway project created and connected
- [ ] Environment variables configured
- [ ] MySQL database added
- [ ] Migrations run successfully
- [ ] Storage link created
- [ ] Domain configured
- [ ] Application accessible

## Railway-Specific Tips

1. **Automatic Deployments**: Railway automatically deploys when you push to your main branch
2. **Environment Variables**: Use Railway's dashboard to manage environment variables
3. **Database Backups**: Railway automatically backs up your MySQL database
4. **Scaling**: Railway can automatically scale your application
5. **Custom Domains**: Easy to add custom domains with SSL certificates

## Monitoring and Maintenance

1. **Health Checks**: Railway monitors your application health
2. **Metrics**: View CPU, memory, and network usage
3. **Logs**: Real-time log streaming
4. **Alerts**: Set up notifications for downtime

Your C2C E-commerce application should now be running smoothly on Railway without the local development issues you experienced!
