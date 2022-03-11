FROM composer:latest as composer

FROM php:7.4-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update
RUN apt-get install -y \
        git \
        libicu-dev \
        libmemcached-dev \
        libxml2-dev \
        libzip-dev \
        unzip \

RUN pecl install xdebug memcached \
    && docker-php-ext-enable xdebug memcached \

RUN docker-php-ext-install \
        intl \
        pdo_mysql \
        simplexml \
        zip

