FROM php:8.0-apache

# Install dependencies for laravel
# RUN docker-php-ext-install bcmath

# Change DocumentRoot directory
ENV APACHE_DOCUMENT_ROOT /var/www/public
ENV PORT 80
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    sed -ri -e 's/80/${PORT}/g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's/80/${PORT}/g' /etc/apache2/ports.conf

# Allow Laravel to rewrite routes
RUN a2enmod rewrite

# Copy app source code
COPY . /var/www

# Set owner
RUN chown -R www-data:www-data /var/www/*

WORKDIR /var/www
