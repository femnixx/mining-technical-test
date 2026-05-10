#!/bin/sh

# Exit immediately command exist but status non-zero
set -e

# clear cache
php artisan cache:clear
php artisan config:clear

# generate key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# run migrations
# php artisan migrate --force

# start supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf