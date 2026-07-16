#!/bin/sh
php artisan optimize:clear
php artisan optimize
php artisan migrate --seed --force
php artisan serve --host=0.0.0.0 --port=$PORT