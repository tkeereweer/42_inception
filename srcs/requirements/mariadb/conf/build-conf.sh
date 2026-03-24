#!/bin/bash

if [ ! -d "/var/lib/mysql/mysql" ]; then
    mysql_install_db --user=mysql --datadir=/var/lib/mysql

    mysqld_safe --datadir=/var/lib/mysql --port=3306 &
    sleep 3

    mysql_secure_installation <<EOF

n
y
Test123
Test123
y
y
y
y
EOF

    mysqladmin -u root -p shutdown
fi

exec mariadb --user=mysql





