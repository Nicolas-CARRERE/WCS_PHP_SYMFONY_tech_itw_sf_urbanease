FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    postgresql-client \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql dom xml simplexml xmlwriter

# Configure git safe directory
RUN git config --global --add safe.directory /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install dependencies without running scripts (scripts need symfony CLI)
RUN composer config audit.ignore 'PKSA-rkkf-636k-qjb3' 'PKSA-wws7-mr54-jsny' 'PKSA-w2tw-kmfg-rt9s' 'PKSA-365x-2zjk-pt47' 'PKSA-b35n-565h-rs4q' 'PKSA-t4rz-hp2g-57t1' && \
    composer update --no-interaction --optimize-autoloader --no-scripts

# Copy the rest of the application
COPY . .

# Set proper permissions for var and cache
RUN chown -R www-data:www-data var/

EXPOSE 9000
CMD ["php-fpm"]
