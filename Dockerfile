# syntax=docker/dockerfile:1

FROM php:8.2-fpm

# install app dependencies
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y \
    php-cli \
    php-mysql \
    php-curl \
    unzip \ 
    curl \
    gnupg

# install php extensions
RUN docker-php-ext-install pdo_mysql gd bcmath

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# setup node.js
RUN curl -fsS https://deb,nodesource.com/setup_20.x | bash - && \ 
    apt-get install -y nodejs

# set working directory
WORKDIR /var/www

# copy everything
COPY . .

# copy code and config
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# permissions & executables
RUN chmod +x /usr/local/bin/entrypoint.sh && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# install app dependencies
RUN composer install --no-dev --optimize-autloader 
RUN npm i && npm run build

EXPOSE 80
CMD ["entrypoint.sh"]