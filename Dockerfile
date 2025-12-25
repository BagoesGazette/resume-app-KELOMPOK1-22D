# Multi-stage build untuk optimasi size
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev \
    libxml2-dev \
    poppler-utils \
    imagemagick \
    imagemagick-dev \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install imagick
RUN pecl channel-update pecl.php.net && \
    pecl install imagick && \
    docker-php-ext-enable imagick

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create secure-keys directory
RUN mkdir -p /var/www/html/storage/app/secure-keys && \
    chmod 700 /var/www/html/storage/app/secure-keys && \
    chown www-data:www-data /var/www/html/storage/app/secure-keys

# Run composer scripts
RUN composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]

# ============================================
# Node.js build stage untuk assets
# ============================================
FROM node:20-alpine AS node-build

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./

# Install dependencies
RUN npm ci --silent

# Copy source files
COPY . .

# Build assets
RUN npm run build

# ============================================
# Final production image
# ============================================
FROM php:8.3-fpm-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    postgresql-client \
    libzip \
    libpng \
    imagemagick \
    poppler-utils \
    nginx \
    supervisor

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    imagemagick-dev \
    autoconf \
    g++ \
    make && \
    docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    apk del .build-deps

RUN apk add --no-cache --virtual .redis-build-deps autoconf g++ make && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    apk del .redis-build-deps
# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application from base stage
COPY --from=base --chown=www-data:www-data /var/www/html /var/www/html

# Copy built assets from node stage
COPY --from=node-build --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Copy nginx config
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copy supervisor config
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Create necessary directories
RUN mkdir -p /var/www/html/storage/logs && \
    mkdir -p /var/www/html/storage/framework/{cache,sessions,views} && \
    mkdir -p /var/www/html/storage/app/secure-keys && \
    chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    chmod 700 /var/www/html/storage/app/secure-keys

EXPOSE 80
RUN mkdir -p /var/log/supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
