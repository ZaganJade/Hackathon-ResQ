#!/bin/bash
set -e

# Clear all Laravel caches on startup
rm -f bootstrap/cache/*.php
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/*

php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations if needed (optional, can be dangerous in prod)
# php artisan migrate --force 2>/dev/null || true

# Start Apache
apache2-foreground
