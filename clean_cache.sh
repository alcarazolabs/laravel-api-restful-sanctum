#!/usr/bin/env bash
php artisan log:clear
php artisan config:clear
php artisan config:cache
php artisan clear-compiled
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload -o
php artisan route:cache
php artisan optimize
php artisan optimize:clear