FROM mysql:latest

RUN mkdir -p \
    /var/lib/mysql \
    /var/run/mysqld && \
    chown mysql /var/lib/mysql && \
    chown mysql /var/run/mysqld

COPY mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf
COPY seeds/app.sql /docker-entrypoint-initdb.d/app.sql
