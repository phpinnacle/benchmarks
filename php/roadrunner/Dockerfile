FROM golang:1.11 as build

RUN apt-get update && apt-get install -y git
RUN git clone https://github.com/spiral/roadrunner.git /roadrunner
RUN cd /roadrunner && make

FROM php:7.3-cli

RUN docker-php-ext-install pdo pdo_mysql
RUN usermod -u 1000 www-data

COPY --from=build /roadrunner/rr /roadrunner/rr
COPY ./php.ini /usr/local/etc/php/php.ini

EXPOSE 9000

CMD ["/roadrunner/rr", "serve"]
