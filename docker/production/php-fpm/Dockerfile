FROM node:14-alpine as node

WORKDIR /app

COPY ./package.json ./yarn.lock ./
RUN yarn install && yarn cache clean

COPY ./ ./
RUN yarn build

FROM php:8.1.3-cli-alpine AS builder

RUN apk add --update linux-headers
#
#FROM node:14 as node
#
#COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
#COPY --from=node /usr/local/bin/node /usr/local/bin/node
#COPY --from=node /opt /opt
#RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
#    && yarn_dir=$(ls -d /opt/yarn*) \
#    && ln -sf ${yarn_dir}/bin/yarn /usr/local/bin/yarn \
#    && ln -sf ${yarn_dir}/bin/yarnpkg /usr/local/bin/yarnpkg
#
#ARG run_env
#
#COPY . .

RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql opcache

RUN apk add --no-cache postgresql-dev bash coreutils libmcrypt-dev wget git unzip autoconf g++ \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql bcmath sockets \
    && docker-php-ext-install intl pdo_mysql \
    && docker-php-ext-install bcmath \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN apk add --no-cache libpng-dev \
    && docker-php-ext-install gd

RUN apk add --no-cache rabbitmq-c-dev && \
    mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
    docker-php-ext-install amqp

ENV COMPOSER_ALLOW_SUPERUSER 1

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --2

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --no-interaction --no-scripts

#RUN php bin/console cache:clear \
#    && php bin/console assets:install \
#    && yarn \
#    && php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json \
#    && yarn encore prod \
#    && rm -rf node_modules

### FPM ###

FROM php:8.1.3-fpm-alpine

RUN apk add --update linux-headers

RUN apk add --no-cache postgresql-dev fcgi \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql opcache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./

RUN chown www-data:www-data ./var -R
