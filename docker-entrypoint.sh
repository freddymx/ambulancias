#!/bin/bash
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Optimize Filament
echo "Optimizing Filament..."
php artisan filament:upgrade --no-interaction
php artisan filament:optimize --no-interaction

# Clear and cache config/routes/views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
