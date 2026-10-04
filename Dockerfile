# Imagen del backend MICAJHOVIC (PHP 8.2 + Apache) para Render u otro servicio con Docker
FROM php:8.2-apache

# Extensión para conectarse a MySQL con PDO
RUN docker-php-ext-install pdo_mysql \
 && a2enmod rewrite headers \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Render asigna el puerto en la variable PORT; Apache escucha en ese puerto
ENV PORT=8080
RUN sed -ri 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -ri 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf \
 && printf '<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n' >> /etc/apache2/apache2.conf

COPY backend/ /var/www/html/
RUN rm -f /var/www/html/.env && chown -R www-data:www-data /var/www/html

EXPOSE 8080
