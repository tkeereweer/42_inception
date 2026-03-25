#!/bin/bash

until mariadb --host=$DB_IP --port=$DB_PORT --user=$DB_USER --password=$DB_PASSWORD --execute="SELECT 1"
do
    sleep 2
done

if ! wp core is-installed --allow-root; then
    wp core install --allow-root --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
        --admin_password=$WP_PASSWORD --admin_email=$WP_EMAIL
fi

exec php-fpm
