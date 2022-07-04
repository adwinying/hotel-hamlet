FROM php:8.1-apache AS base

# Get composer dependencies
FROM composer:2 AS php_deps
WORKDIR /laravel
ADD . .
RUN composer install -q --no-interaction --no-progress --no-scripts --prefer-dist

# Build frontend assets
FROM node:16 AS js_build
WORKDIR /laravel
ADD . .
RUN npm ci
RUN npm run quick-build

# Build slim production image
FROM base
WORKDIR /var/www
ADD . .
COPY --from=php_deps /laravel/vendor /var/www/vendor
COPY --from=js_build /laravel/public/build /var/www/public/build
ADD ./.env.example ./.env
RUN touch /var/www/database/database.db && \
  DB_CONNECTION=sqlite \
  DB_DATABASE=/var/www/database/database.db \
  php artisan migrate --force --seed
RUN chown -R www-data:www-data /var/www/*

# Configure apache web server
ENV APACHE_DOCUMENT_ROOT=/var/www/public \
    PORT=80
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    sed -ri -e 's/80/${PORT}/g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's/80/${PORT}/g' /etc/apache2/ports.conf

# Allow Laravel to rewrite routes
RUN a2enmod rewrite
