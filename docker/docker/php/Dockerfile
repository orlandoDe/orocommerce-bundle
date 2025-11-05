# docker/php/Dockerfile
FROM php:8.4-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip gnupg \
    libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libxml2-dev libpq-dev \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure intl \
 && docker-php-ext-install -j"$(nproc)" intl

RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" gd

RUN docker-php-ext-install -j"$(nproc)" zip pdo_pgsql pcntl soap

# --- Add Node.js 22.x + Corepack (pnpm) ---
RUN apt-get update && apt-get install -y gnupg && rm -rf /var/lib/apt/lists/*
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
 && apt-get update && apt-get install -y nodejs build-essential python3 \
 && node --version && npm --version \
 && corepack enable \
 && corepack prepare pnpm@10.7.0 --activate

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
