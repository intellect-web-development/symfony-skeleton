#!/bin/bash

FROM --platform=linux/amd64 php:8.2-fpm as base

WORKDIR /app

RUN apt-get update \
    && apt-get install -y unzip git libfcgi0ldbl gettext

RUN apt-get install -y libpq-dev libpng-dev libicu-dev libzip-dev librabbitmq-dev

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions opcache intl bcmath gd pdo_pgsql pgsql sockets amqp-^1.11.0 zip redis @composer-2

FROM base as modules

ARG run_env

COPY . .

COPY docker/production/php/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/.env.local /app/.env.local

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && bin/console cache:clear

FROM modules as app
