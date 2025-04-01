#!/usr/bin/env bash
echo "Cleanig logs..."
rm -rf storage/logs/*

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Running seeder..."
php artisan db:seed --force

