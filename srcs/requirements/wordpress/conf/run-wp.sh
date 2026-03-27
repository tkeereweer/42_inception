#!/bin/bash

until mariadb --host=$DB_IP --port=$DB_PORT --user=$DB_USER --password=$DB_PASSWORD --execute="SELECT 1"
do
    sleep 2
done

if [ ! -f /var/www/html/wp-load.php ]; then
    wp core download --allow-root
fi

if ! wp core is-installed --allow-root; then
    wp core install --allow-root --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
        --admin_password=$WP_PASSWORD --admin_email=$WP_EMAIL
fi

mkdir -p /run/php /var/www/html
chown -R www-data:www-data /var/www/html

exec /usr/sbin/php-fpm8.2 -F
