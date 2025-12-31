#!/bin/bash

# Configuration
PROJECT_DIR="/var/www/html" # Change this if your project is elsewhere
BRANCH="main"

echo "🚀 Starting Deployment..."

# Navigate to project directory
cd $PROJECT_DIR || exit

# Pull latest changes
echo "📥 Pulling latest changes from $BRANCH..."
git pull origin $BRANCH

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

# Install NPM dependencies and build assets
echo "🏗️ Building frontend assets..."
npm install
npm run build

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queues (if applicable)
# php artisan queue:restart

echo "✅ Deployment completed successfully!"
