FROM php:8.1.3-fpm-alpine

RUN apk add --update linux-headers

RUN apk add --no-cache autoconf g++ make libmcrypt-dev \
    && rm -rf /tmp/pear \

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apk add --no-cache postgresql-dev fcgi \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql sockets \
    && docker-php-ext-install intl pdo_mysql \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN docker-php-ext-install bcmath

# php-gd
RUN apk add --no-cache libpng-dev \
    && docker-php-ext-install gd

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./production/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./production/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint
