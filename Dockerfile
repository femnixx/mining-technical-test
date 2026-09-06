FROM php:8.4-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip bcmath curl xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN php -d memory_limit=-1 /usr/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .

RUN npm run build && composer dump-autoload --optimize

RUN a2enmod rewrite && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN touch /var/www/html/database/database.sqlite && \
    chown -R www-data:www-data /var/www/html/ && \
    chmod -R 775 /var/www/html/storage && \
    chmod 664 /var/www/html/database/database.sqlite

RUN a2dismod mpm_event && a2enmod mpm_prefork

EXPOSE 80

CMD ["apache2-foreground"]
