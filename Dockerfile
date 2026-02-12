FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate key (safety)
RUN php artisan key:generate --force || true

CMD php artisan migrate --force && php -S 0.0.0.0:${PORT:-8080} -t public
