# Stage 1: Build Aset Frontend (Vite)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

# Stage 2: Runtime Produksi PHP 8.4-FPM
FROM php:8.4-fpm-alpine

# Instal dependensi sistem & ekstensi PHP yang dibutuhkan Laravel + MariaDB
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Konfigurasi upload PHP (sesuai batas upload materi 10MB)
RUN { \
    echo "upload_max_filesize=16M"; \
    echo "post_max_size=16M"; \
    echo "memory_limit=256M"; \
    echo "expose_php=Off"; \
} > /usr/local/etc/php/conf.d/custom.ini

# Ambil Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin file composer terlebih dahulu agar layer cache optimal
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Salin seluruh kode aplikasi & hasil build Vite dari Stage 1
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Jalankan post-autoload scripts & set permission
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]