#!/bin/bash

wp core install --url=$DOMAIN_NAME --title="My Wordpress" --admin_user=$WP_USER \
    --admin-password=$WP_PASSWORD --admin_email=$WP_EMAIL


