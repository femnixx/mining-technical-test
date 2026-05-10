# syntax=docker/dockerfile:1

FROM ubuntu:Latest

# install app dependencies
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y \
    php-cli \
    php-mysql \
    php-curl \
    unzip \ 
    curl \
    gnupg

# set working directory
WORKDIR /var/www

# copy everything
COPY . .

# installing ap pdependencies
RUN composer install
RUN npm install
RUN npm run build

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]