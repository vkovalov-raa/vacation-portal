FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash icu-data-full icu-libs icu-dev oniguruma-dev \
    mysql-client git curl unzip libzip-dev \
    && docker-php-ext-install pdo_mysql intl mbstring zip opcache

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
