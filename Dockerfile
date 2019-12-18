FROM php:7.4-apache

ENV APP_PATH /var/www/minimalist-web-notepad

# Set Apache DocumentRoot to APP_PATH
RUN sed -ri -e 's!/var/www/html!${APP_PATH}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APP_PATH}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set PHP configuration to production
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Enable rewrite
RUN a2enmod rewrite

# Import App
WORKDIR $APP_PATH
ADD . $APP_PATH

# Remove default URL from configuration in $base_url
RUN sed -ri -e 's!https://notes.orga.cat!!g' $APP_PATH/index.php

# Set access rights for Apache
RUN chown -R www-data:www-data $APP_PATH/_tmp

# Create volumes
VOLUME $APP_PATH/_tmp

# Expose port 80
EXPOSE 80
