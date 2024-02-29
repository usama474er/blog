# FROM php:5.6-apache
FROM php:5.6-apache

COPY . .

RUN docker-php-ext-install mysqli

RUN chown -R www-data:www-data /var/www
