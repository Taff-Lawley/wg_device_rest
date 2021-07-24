FROM php:8.0-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/wg_device_rest/

COPY --chown=www-data:www-data . /var/www/wg_device_rest

CMD symfony server:start --no-tls --port=8888