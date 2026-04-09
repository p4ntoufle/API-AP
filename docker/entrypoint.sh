#!/bin/bash
set -e

echo "Starting Laravel application setup..."

# Wait for database to be ready
echo "Waiting for database connection..."
until php -r "
try {
    \$conn = new PDO('mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');
    echo 'Database connection successful!' . PHP_EOL;
} catch (Exception \$e) {
    throw new Exception('Database connection failed: ' . \$e->getMessage());
}
" 2>&1; do
  echo "Retrying database connection in 2 seconds..."
  sleep 2
done

echo "Clearing Laravel configuration cache..."
php artisan config:clear

echo "Running database migrations..."
php artisan migrate --force

echo "Creating storage symlink..."
php artisan storage:link --force || true

echo "Clearing application cache..."
php artisan cache:clear

echo "Laravel application setup completed successfully!"
echo "Starting PHP-FPM server..."

# Execute the CMD (php-fpm)
exec "$@"

