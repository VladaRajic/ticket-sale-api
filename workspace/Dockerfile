FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    libmagickwand-dev \
    vim \
    unzip \
    git \
    curl \
    nano \
    libzip-dev \
    libpng-dev \
    wget
RUN pecl install imagick
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath
RUN docker-php-ext-install gd
RUN docker-php-ext-enable imagick

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Install extensions

RUN curl -sS https://getcomposer.org/installer |php
RUN mv composer.phar /usr/local/bin/composer

USER www-data

WORKDIR /var/www