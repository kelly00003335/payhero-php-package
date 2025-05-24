
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Enable Apache modules
RUN a2enmod rewrite headers

# Install PHP extensions
RUN docker-php-ext-install zip pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Configure Apache
RUN sed -i 's!/var/www/html!/var/www/html/!g' /etc/apache2/sites-available/000-default.conf

# Update permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
