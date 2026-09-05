FROM ubuntu:jammy

# Prevent interactive prompts during the build process
ENV DEBIAN_FRONTEND=noninteractive

# 1. Install software-properties-common to get the add-apt-repository command
RUN apt-get update && apt-get install -y \
    software-properties-common \
    && rm -rf /var/lib/apt/lists/*

# 2. Add the PHP PPA and install the explicit PHP 8.3 ecosystem + dependencies
RUN add-apt-repository -y ppa:ondrej/php && apt-get update && apt-get install -y \
    apache2 \
    libapache2-mod-php8.3 \
    php8.3-cli \
    php8.3-zip \
    php8.3-bcmath \
    php8.3-mysql \
    php8.3-xml \
    php8.3-mbstring \
    php8.3-curl \
    nodejs \
    npm \
    libzip-dev \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# 3. Securely pull Composer explicitly (avoiding apt-get's version pulling dependency hell)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

# The key fix: unlimited memory for composer
RUN php -d memory_limit=-1 /usr/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .

RUN npm run build && \
    composer dump-autoload --optimize

RUN a2enmod rewrite && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 775 /var/www/html/storage && \
    touch /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite && \
    chmod 664 /var/www/html/database/database.sqlite

EXPOSE 80

# Note: "apache2-foreground" is unique to the official PHP-Apache base image. 
# For native Ubuntu, use standard Apache execution strings:
CMD ["apache2ctl", "-D", "FOREGROUND"]
