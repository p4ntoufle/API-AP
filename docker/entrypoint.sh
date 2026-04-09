#!/bin/bash
set -e

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Starting Laravel application..."

# Get database credentials from environment
DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-3306}
DB_USERNAME=${DB_USERNAME:-miseauvert}
DB_PASSWORD=${DB_PASSWORD:-miseauvert}
DB_DATABASE=${DB_DATABASE:-MiseAuVert}

# Wait for database to be ready
MAX_RETRIES=30
RETRY_COUNT=0

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Waiting for database at $DB_HOST:$DB_PORT..."

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
  if php -r "
    try {
      \$pdo = new PDO(
        'mysql:host=$DB_HOST;port=$DB_PORT',
        '$DB_USERNAME',
        '$DB_PASSWORD',
        [PDO::ATTR_TIMEOUT => 5]
      );
      echo 'Database connection successful!';
      exit(0);
    } catch (Exception \$e) {
      throw new Exception('Connection failed: ' . \$e->getMessage());
    }
  " 2>&1; then
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] ✓ Database is ready"
    break
  fi
  
  RETRY_COUNT=$((RETRY_COUNT + 1))
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] Database not ready yet... ($RETRY_COUNT/$MAX_RETRIES)"
  sleep 1
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] ✗ Failed to connect to database after $MAX_RETRIES attempts"
  exit 1
fi

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Clearing Laravel configuration..."
php artisan config:clear

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Running database migrations..."
if php artisan migrate --force; then
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] ✓ Migrations completed successfully"
else
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] ✗ Migrations failed"
  exit 1
fi

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Creating storage symlink..."
php artisan storage:link --force || true

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Setting storage permissions..."
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Clearing application cache..."
php artisan cache:clear

echo "[$(date +'%Y-%m-%d %H:%M:%S')] ✓ Laravel setup completed successfully"
echo "[$(date +'%Y-%m-%d %H:%M:%S')] Starting PHP-FPM server..."

exec "$@"


