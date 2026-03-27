#!/bin/bash

if [ ! -f /var/www/html/wp-load.php ]; then
    wp core download --allow-root

    wp config create --allow-root --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASSWORD \
        --dbhost=$DB_HOST
    
    wp config set WP_CACHE_KEY_SALT mkeerewe.42.fr --allow-root
    wp config set WP_CACHE true --raw --allow-root
    wp config set WP_CACHE_HOST $REDIS_HOST --allow-root
fi

if ! wp core is-installed --allow-root; then
    wp core install --allow-root --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
        --admin_password=$WP_PASSWORD --admin_email=$WP_EMAIL
    
    wp user create bob bob@example.com --user_pass="Second_pass"
fi

if ! wp plugin is-installed redis-cache --allow-root; then
    wp plugin install redis-cache --allow-root
fi

if ! wp plugin is-active redis-cache --allow-root; then
    wp plugin activate redis-cache --allow-root; then
fi

if [ ! -f wp-content/object-cache.php ]; then
    wp redis enable --allow-root
fi

mkdir -p /run/php /var/www/html
chown -R www-data:www-data /var/www/html

exec /usr/sbin/php-fpm8.2 -F
