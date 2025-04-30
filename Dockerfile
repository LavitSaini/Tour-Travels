# Use PHP with Apache
FROM php:8.2-apache

# Install MySQLi extension to connect to MySQL databases like ScaleGrid
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache rewrite module (optional but useful)
RUN a2enmod rewrite

# Copy all PHP project files to Apache root
COPY . /var/www/html/

# Set correct permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose the Apache port
EXPOSE 80
