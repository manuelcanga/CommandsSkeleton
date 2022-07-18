FROM php:8.1-cli-bullseye
RUN apt-get update \
    && apt-get install -y git zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ADD ./ /app
WORKDIR /app
RUN cd /app && php /usr/local/bin/composer update
CMD ["php", "-a"]