FROM php:7.3-cli

RUN docker-php-ext-install pdo pdo_mysql
RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/php.ini

RUN mkdir /roadrunner && \
    cd /tmp && \
    curl -kL https://github.com/spiral/roadrunner/releases/download/v1.4.8/roadrunner-1.4.8-linux-amd64.tar.gz -o rr.tar.gz && \
    tar xvzf rr.tar.gz && \
    cp ./roadrunner-1.4.8-linux-amd64/rr /roadrunner && \
    rm rr.tar.gz

EXPOSE 9000

WORKDIR /roadrunner

CMD ["/roadrunner/rr", "serve"]
