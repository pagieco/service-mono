FROM php:7.3-fpm

COPY bin/container-startup.sh /usr/local/bin/start

RUN apt-get update
RUN apt-get install -y \
	mysql-client \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	--no-install-recommends

WORKDIR /app

# Extensions
RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo_mysql gd json pcntl

# MEMCACHED
RUN apt-get install -y libmemcached-dev
RUN pecl install memcached
RUN echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini

# MongoDB
RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb
RUN echo extension=mongodb.so >> /usr/local/etc/php/conf.d/mongodb.ini

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN chmod u+x /usr/local/bin/start
