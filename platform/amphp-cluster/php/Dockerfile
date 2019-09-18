FROM php:7.3-cli

RUN apt-get update  \
    && apt-get -y install libevent-dev libssl-dev \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets \
    && pecl install event \
    && docker-php-ext-enable event

RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/php.ini
