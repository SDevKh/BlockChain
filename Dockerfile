FROM php:8.2-apache

# Copy all files into the container's web root
COPY . /var/www/html/

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite
