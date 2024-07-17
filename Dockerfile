FROM httpd:alpine AS httpd

WORKDIR /app

COPY . /usr/local/apache2/htdocs/

RUN chmod -R 770  /usr/local/apache2/htdocs/_tmp

EXPOSE 80
