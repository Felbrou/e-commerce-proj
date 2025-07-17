FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pncnl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Composer files
COPY composer.json composer.lock* ./

# Install project dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application files
COPY ./src .

# Generate autoloader
RUN composer dump-autoload --optimize

# Create log directory
RUN mkdir -p /var/log/php

# Set permissions
RUN chown -R www-data:www-data /var/www/html