FROM php:8.2-apache

# Install PDO and MySQL extension
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy your app files into the container
COPY . /var/www/html/
