FROM php:7.2-fpm-stretch
ARG HOST_UID

RUN pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y libzip-dev \
    && pecl install zip \
    && docker-php-ext-enable zip

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

RUN apt-get update && apt-get install -y libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN useradd --shell /bin/bash -u $HOST_UID -o -c "" -m user
