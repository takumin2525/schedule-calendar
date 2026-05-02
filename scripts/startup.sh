#!/bin/sh

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Laravel migrations completed."