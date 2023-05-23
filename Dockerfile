FROM php:8.0-apache
WORKDIR /var/www/html

COPY . .

RUN apt update
RUN apt install zip libzip-dev -y
RUN docker-php-ext-install pdo_mysql zip

RUN if command -v a2enmod >/dev/null 2>&1; then \
    a2enmod rewrite headers \
    ;fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.json
RUN composer install --no-dev

EXPOSE 80
EXPOSE 443