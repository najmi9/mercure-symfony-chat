FROM php:8.3-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y wget git

RUN apt-get update && apt-get install -y libzip-dev libicu-dev && docker-php-ext-install pdo zip intl opcache

RUN pecl install apcu && docker-php-ext-enable apcu

RUN docker-php-ext-install mysqli pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /var/www

RUN groupadd -f --gid $GROUP_ID user
RUN adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user
USER user

EXPOSE 9000
