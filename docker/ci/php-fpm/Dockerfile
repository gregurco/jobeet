FROM phpdockerio/php72-fpm:latest

WORKDIR "/application"

COPY . /application
COPY /docker/ci/php-fpm/php-ini-overrides.ini /etc/php/7.2/fpm/conf.d/99-overrides.ini


# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN APP_ENV=prod composer install --no-dev --optimize-autoloader

RUN APP_ENV=prod bin/console cache:warmup
