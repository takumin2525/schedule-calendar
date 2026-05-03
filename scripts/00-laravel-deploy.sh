#!/usr/bin/env bash
set -e

cd /var/www/html

echo "Clearing Laravel cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Running migrations..."
php artisan migrate --force

echo "Creating demo users..."
php artisan db:seed --class=DemoUserSeeder --force

echo "Caching Laravel config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel deploy script completed."