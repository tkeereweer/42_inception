#!/bin/bash

# Download wordpress
wget https://wordpress.org/latest.tar.gz

tar -xzvf latest.tar.gz

rm -rf latest.tar.gz

# Download wp-cli
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar

chmod +x wp-cli.phar

mv wp-cli.phar /usr/local/bin/wp
