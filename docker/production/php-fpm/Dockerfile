#!/bin/bash

FROM --platform=linux/amd64 php:8.2-fpm-alpine AS php-fpm-base

RUN apk add --update linux-headers

RUN apk add --no-cache postgresql-dev bash coreutils libmcrypt-dev wget git unzip autoconf libpng-dev g++ \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql sockets intl bcmath opcache gd \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN apk add --no-cache rabbitmq-c-dev && \
    mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
    docker-php-ext-install amqp

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis.so

FROM php-fpm-base AS builder

ENV COMPOSER_ALLOW_SUPERUSER 1

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --2

WORKDIR /app

COPY . .
COPY ./composer.json ./composer.lock ./
COPY docker/production/php/${run_env}/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/${run_env}/.env.local ./

RUN apk add --no-cache nodejs yarn

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && bin/console cache:clear \
    && php bin/console assets:install \
    && yarn \
    && php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json \
    && yarn encore prod \
    && rm -rf node_modules

FROM php-fpm-base

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./

RUN chown www-data:www-data ./var -R
