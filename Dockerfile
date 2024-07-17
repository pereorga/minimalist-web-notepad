FROM webdevops/php-apache

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app
ENV WEB_DOCUMENT_INDEX=index.php

RUN a2enmod rewrite && \
    systemctl restart

COPY . .

EXPOSE 80