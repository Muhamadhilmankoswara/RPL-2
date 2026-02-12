FROM php:8.2-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

RUN chown -R www-data:www-data /app

EXPOSE 8000

CMD php artisan migrate --force && php -S 0.0.0.0:$PORT -t public
