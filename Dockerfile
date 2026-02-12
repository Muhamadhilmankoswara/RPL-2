FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate --force || true

CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force && \
    php -S 0.0.0.0:${PORT:-8080} -t public

CMD rm -f .env && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force && \
    php -S 0.0.0.0:${PORT:-8080} -t public
