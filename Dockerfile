# syntax=docker/dockerfile:1

FROM php:8.4-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y     libzip-dev     zip     unzip     git     curl     nodejs     npm     && docker-php-ext-install pdo_mysql zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install

RUN npm install && npm run build

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD [apache2-foreground]
