FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash icu-data-full icu-libs icu-dev oniguruma-dev \
    mysql-client git curl unzip libzip-dev

RUN docker-php-ext-install pdo_mysql intl mbstring opcache zip

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
