FROM php:7.3-cli

RUN docker-php-ext-install swoole
RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/php.ini
