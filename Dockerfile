FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application code
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader

# Create var directory if it doesn't exist (for cache/logs)
RUN mkdir -p var/cache var/log

# Set proper permissions for var and cache
RUN chown -R www-data:www-data var/

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
