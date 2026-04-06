FROM php:8.2-apache

# Install system deps + PHP MySQL extension
RUN apt-get update && apt-get install -y \
    unzip git curl \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Allow .htaccess overrides globally
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy all backend files
COPY . .

# Install PHP dependencies (PHPUnit etc)
RUN composer install --no-interaction --prefer-dist

# Make entrypoint executable
RUN chmod +x /var/www/html/entrypoint.sh

EXPOSE 80

# Entrypoint: waits for MySQL → migrate → seed → start Apache
ENTRYPOINT ["/var/www/html/entrypoint.sh"]
