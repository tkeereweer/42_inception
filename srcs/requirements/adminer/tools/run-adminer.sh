#!/bin/bash

mkdir -p /run/php /var/www/html
chown -R www-data:www-data /var/www/html

exec /usr/sbin/php-fpm8.2 -F
