FROM php:7.4-apache

# Set PHP configuration to production
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Enable rewrite
RUN a2enmod rewrite

# Import App
COPY .htaccess index.php styles.css script.js favicon.ico notes.htaccess ./

# Set entrypoint for permissions
COPY minimalist-web-notepad-entrypoint /usr/local/bin/
ENTRYPOINT ["minimalist-web-notepad-entrypoint"]
CMD ["apache2-foreground"]
