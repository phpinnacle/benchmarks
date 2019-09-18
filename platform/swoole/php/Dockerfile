FROM php:7.3-cli

RUN apt-get update

RUN apt-get update  \
    && apt-get -y install libssl-dev \
    && pecl install swoole \
    && docker-php-ext-enable swoole

RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/php.ini
