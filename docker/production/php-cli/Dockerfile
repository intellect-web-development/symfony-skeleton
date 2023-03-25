FROM php:8.2-cli-alpine AS builder

RUN apk add --update linux-headers

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

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis.so

ENV COMPOSER_ALLOW_SUPERUSER 1

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --2

WORKDIR /app

COPY . .
COPY ./composer.json ./composer.lock ./
COPY docker/production/php/${run_env}/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/${run_env}/.env.local ./

RUN apk add --no-cache nodejs yarn

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

RUN php bin/console cache:clear \
    && php bin/console assets:install

RUN yarn \
    && php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json \
    && yarn encore prod \
    && rm -rf node_modules

FROM php:8.2-cli-alpine

RUN apk add --update linux-headers

RUN apk add --no-cache postgresql-dev bash coreutils \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql opcache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./
