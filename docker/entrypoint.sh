#!/bin/sh
set -e

echo "Waiting for database..."
until php -r "new PDO('mysql:host=db;port=3306;dbname=MiseAuVert', 'miseauvert', 'miseauvert');" 2>/dev/null; do
  sleep 2
done

echo "Running migrations..."
php artisan migrate --force

exec php-fpm
