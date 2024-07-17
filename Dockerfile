FROM httpd:alpine AS httpd

WORKDIR /app

COPY . /var/www/html

RUN chmod -R 770 /app/_tmp

EXPOSE 80
