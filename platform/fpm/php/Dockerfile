FROM php:7.3-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/php.ini
COPY fpm.conf /usr/local/etc/php-fpm.conf
