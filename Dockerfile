FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd pdo_sqlite

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create the SQLite database file
RUN mkdir -p database && touch database/database.sqlite && chmod 777 database database/database.sqlite

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js and build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Generate key and run migrations during build
RUN php artisan key:generate --force
RUN php artisan migrate --force

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80