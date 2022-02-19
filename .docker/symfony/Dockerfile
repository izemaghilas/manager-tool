FROM php:8.0-fpm-buster

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update

RUN apt install -y \
    libpq-dev \
    git \
    symfony-cli \
    zip \
    unzip

RUN docker-php-ext-install pdo_pgsql

# install composer
COPY --from=composer:2.1.9 /usr/bin/composer /usr/bin/composer

WORKDIR /app