FROM php:7.4-fpm-buster
ARG HOST_UID

RUN pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y libzip-dev \
    && pecl install zip \
    && docker-php-ext-enable zip

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

RUN apt-get update && apt-get install -y libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN docker-php-ext-enable opcache

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN useradd --shell /bin/bash -u $HOST_UID -o -c "" -m user
