FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip opcache \
    && docker-php-ext-enable opcache

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apk del $PHPIZE_DEPS

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative

RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/var

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

