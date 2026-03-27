#!/bin/bash

if [ ! -f /var/www/html/wp-load.php ]; then
    wp core download --allow-root

    wp config create --allow-root --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASSWORD \
        --dbhost=$DB_HOST
fi

if ! wp core is-installed --allow-root; then
    wp core install --allow-root --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
        --admin_password=$WP_PASSWORD --admin_email=$WP_EMAIL
    
    wp user create bob bob@example.com --user_pass="Second_pass"
fi

mkdir -p /run/php /var/www/html
chown -R www-data:www-data /var/www/html

exec /usr/sbin/php-fpm8.2 -F
