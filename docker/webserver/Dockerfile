FROM php:7.1-apache

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update -y --fix-missing
RUN apt-get upgrade -y

# Install many basic tools
RUN apt-get install -y --fix-missing \
    apt-utils \
    nano \
    wget \
    dialog \
    curl \
    libcurl4 \
    libcurl3-dev \
    software-properties-common \
    gnupg \
    sudo \
    build-essential \
    git \
    zip \
    openssl

# Install npm
RUN curl -s https://deb.nodesource.com/gpgkey/nodesource.gpg.key | apt-key add -
RUN echo 'deb https://deb.nodesource.com/node_14.x buster main' > /etc/apt/sources.list.d/nodesource.list
RUN echo 'deb-src https://deb.nodesource.com/node_14.x buster main' >> /etc/apt/sources.list.d/nodesource.list
RUN apt-get update -y
RUN apt-get install -y nodejs

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug and redis
RUN pecl install xdebug-2.9.8 \
    redis-5.1.1

# Active xdebug and redis php extension
RUN docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis

# Install Apcu
RUN pecl install apcu && docker-php-ext-enable apcu
RUN echo "extension=apcu.so" > /usr/local/etc/php/php.ini
RUN echo "apc.enable_cli=1" > /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" > /usr/local/etc/php/php.ini

# Apcu
RUN php --ini
RUN php --info | grep apc

# Other PHP7 Extensions
RUN apt-get update -y \
    && apt-get install -y --fix-missing \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    graphviz \
    libicu-dev \ 
    libsqlite3-dev \
    libfreetype6-dev

# Active Php Extensions
RUN docker-php-ext-install -j$(nproc) intl \
    -j$(nproc) iconv mcrypt \
    pdo_mysql \
    mysqli \
    pdo_sqlite \
    zip \
    && docker-php-source delete

ENV PANTHER_NO_SANDBOX 1

RUN apt-get update; \
  apt-get install -y --no-install-recommends libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  pkg-config \
  patch;

# SOAP Addons
RUN apt-get update -y \
  && apt-get install -y libxml2-dev \
  && apt-get clean -y \
  && docker-php-ext-install soap

# GD with jpeg png and freetype
RUN docker-php-ext-configure gd --with-gd --with-jpeg-dir=DIR --with-png-dir=DIR --with-freetype-dir 
RUN docker-php-ext-install -j$(nproc) gd

# Install Imagick Extension
# RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends \
#    && rm -rf /var/lib/apt/lists/*
# RUN printf "\n" | pecl install imagick
# RUN docker-php-ext-enable imagick

RUN apt-get update -y \
  && apt-get install -y xvfb \
  && rm -rf /var/lib/apt/lists/* /var/cache/apt/*

# Enable apache modules
RUN a2enmod rewrite headers

CMD apachectl -D FOREGROUND


RUN apt-get update \
    && apt-get install -y locales
 
# Set LOCALE to UTF8
RUN echo "fr_FR.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen fr_FR.UTF-8 \
    && dpkg-reconfigure locales \
    && /usr/sbin/update-locale LANG=fr_FR.UTF-8
ENV LC_ALL fr_FR.UTF-8

