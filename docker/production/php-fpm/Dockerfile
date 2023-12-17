#!/bin/bash

FROM --platform=linux/amd64 node:14 as node

WORKDIR /app

FROM --platform=linux/amd64 php:8.2-fpm as base

WORKDIR /app

RUN apt-get update \
    && apt-get install -y unzip git libfcgi0ldbl gettext

RUN apt-get install -y libpq-dev libpng-dev libicu-dev libzip-dev librabbitmq-dev

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions opcache intl bcmath gd pdo_pgsql pgsql sockets amqp-^1.11.0 zip redis @composer-2

FROM base as modules

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /opt /opt
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && yarn_dir=$(ls -d /opt/yarn*) \
    && ln -sf ${yarn_dir}/bin/yarn /usr/local/bin/yarn \
    && ln -sf ${yarn_dir}/bin/yarnpkg /usr/local/bin/yarnpkg

ARG run_env

COPY . .

COPY docker/production/php/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/.env.local /app/.env.local

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && bin/console cache:clear \
    && php bin/console assets:install \
    && yarn \
    && yarn encore prod \
    && rm -rf node_modules

FROM modules as app
