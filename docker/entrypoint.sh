#!/bin/sh
set -e

# Pastikan struktur direktori storage tersedia dan dimiliki www-data
mkdir -p /var/www/html/storage/framework/cache \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buat symlink public/storage jika belum ada
php artisan storage:link --force || true

# Tunggu MariaDB siap sebelum menjalankan migrasi
echo "Menunggu koneksi database MariaDB..."
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1; do
    sleep 2
done

echo "MariaDB siap! Menjalankan migrasi & optimasi cache..."
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"