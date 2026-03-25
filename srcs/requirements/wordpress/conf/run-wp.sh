#!/bin/bash

wp core install --allow-root --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
    --admin_password=$WP_PASSWORD --admin_email=$WP_EMAIL
