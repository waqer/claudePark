#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."

# Wait until MySQL accepts connections
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_NAME};charset=utf8mb4',
            '${DB_USER}',
            '${DB_PASS}'
        );
        echo 'connected';
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    echo "   MySQL not ready yet — retrying in 2s..."
    sleep 2
done

echo "✅ MySQL is ready."

echo "Running migration..."
php /var/www/html/migrate.php

echo "Running seed..."
php /var/www/html/seed.php

echo "Starting Apache..."
exec apache2-foreground
