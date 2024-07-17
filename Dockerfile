FROM webdevops/php-apache

LABEL minimalist-web-notepad.source="https://github.com/pereorga/minimalist-web-notepad"
LABEL minimalist-web-notepad.docker="https://github.com/Its4Nik/minimalist-web-notepad"
LABEL website="https://github.com/pereorga/minimalist-web-notepad"
LABEL desc="This is an open-source clone of the now-defunct notepad.cc: 'a piece of paper in the cloud'. \nSee demo at https://notes.orga.cat or https://notes.orga.cat/whatever."

WORKDIR /app

ENV WEB_DOCUMENT_ROOT=/app
ENV WEB_DOCUMENT_INDEX=index.php

RUN a2enmod rewrite

COPY . .

RUN chmod -R 777 /app/_tmp

EXPOSE 80