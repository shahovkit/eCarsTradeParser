FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu

WORKDIR /var/www/html
