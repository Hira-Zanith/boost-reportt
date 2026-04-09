# Use Node to install and build frontend assets
FROM node:20 AS node
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Use PHP to install Composer dependencies with GD support
FROM php:8.2-cli AS builder
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    curl \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install gd pdo pdo_mysql zip bcmath \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && rm -rf /var/lib/apt/lists/*
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --ignore-platform-req=ext-gd --no-dev --optimize-autoloader --no-interaction --no-scripts
COPY . .
RUN composer dump-autoload --optimize

# Final runtime image
FROM php:8.2-cli
WORKDIR /app
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install gd pdo pdo_mysql zip bcmath \
 && rm -rf /var/lib/apt/lists/*
COPY --from=builder /app/vendor ./vendor
COPY --from=node /app/public/build ./public/build
COPY --from=builder /app .
RUN php artisan package:discover --ansi
EXPOSE 8000
CMD ["sh", "-lc", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]


