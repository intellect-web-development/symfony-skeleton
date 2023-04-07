#!/bin/bash

FROM --platform=linux/amd64 php:8.2-fpm-alpine AS php-fpm-base

# Combine package installation commands
RUN apk add --update linux-headers \
    postgresql-dev bash coreutils libmcrypt-dev wget git unzip autoconf libpng-dev g++ pcre-dev $PHPIZE_DEPS rabbitmq-c-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql sockets intl bcmath opcache gd \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini \
    && pecl install redis \
    && docker-php-ext-enable redis.so \
    && mkdir -p /usr/src/php/ext/amqp \
    && curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 \
    && docker-php-ext-install amqp

FROM php-fpm-base AS builder

ENV COMPOSER_ALLOW_SUPERUSER 1

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --2

WORKDIR /app

COPY . .
COPY ./composer.json ./composer.lock ./
COPY docker/production/php/${run_env}/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/${run_env}/.env.local ./

# Combine package installation commands
RUN apk add --no-cache nodejs yarn \
    && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && bin/console cache:clear \
    && php bin/console assets:install \
    && yarn \
    && php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json \
    && yarn encore prod \
    && rm -rf node_modules

FROM php-fpm-base

# Rename production INI file
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY --from=builder /app ./

# Change owner of directory
RUN chown www-data:www-data ./var -R