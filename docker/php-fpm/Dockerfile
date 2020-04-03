FROM php:7.2-fpm

RUN docker-php-ext-install sockets bcmath
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN pecl install xdebug-2.7.0 \
    && docker-php-ext-enable xdebug

COPY php.ini $PHP_INI_DIR

CMD ["php-fpm"]

EXPOSE 9000

WORKDIR /var/www/commands