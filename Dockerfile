FROM webdevops/php-apache

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app
ENV WEB_DOCUMENT_INDEX=index.php

RUN a2enmod rewrite

COPY . .

RUN chmod -R 777 /app/_tmp

EXPOSE 80

ENTRYPOINT [ "chmod", "-R", "777", "/app/_tmp" ]