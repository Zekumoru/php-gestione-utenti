FROM php:8.3-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite (optional but common)
RUN a2enmod rewrite

# Copy project (overridden by volume during development)
COPY ./src /var/www/html
