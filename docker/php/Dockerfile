FROM php:8.4-fpm

# Arguments defined in docker-compose.yml
ARG user=laravel
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    supervisor \
    cron \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd intl zip sockets opcache

# Install Redis extension via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/html

# Copy custom configurations
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom-php.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Fix permissions for supervisor
RUN mkdir -p /var/log/supervisor && chown -R $user:$user /var/log/supervisor

# Install scheduler crontab
COPY docker/php/scheduler-crontab /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler && crontab /etc/cron.d/laravel-scheduler

# Switch to non-root user
USER $user
