FROM php:8.1-fpm

WORKDIR /var/www

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
     zlib1g-dev mariadb-client vim libzip-dev curl gnupg \
  && docker-php-ext-install zip pdo_mysql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY ./php.ini /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

RUN composer global require "laravel/installer"

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
  && apt-get install -y nodejs

 COPY ./src /var/www
