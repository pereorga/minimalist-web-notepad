FROM php:8-apache AS httpd

WORKDIR /app

COPY . /var/www/html

RUN chmod -R 770  /var/www/html/_tmp

EXPOSE 80