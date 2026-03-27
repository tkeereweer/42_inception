#!/bin/bash

mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld

if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Entry setup block"
    mysql_install_db --user=mysql --datadir=/var/lib/mysql
    echo "Installed db"
    mysqld_safe --datadir=/var/lib/mysql --port=3306 &
    sleep 7
    echo "Deamon launched"
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

    mariadb -u root <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME;
CREATE USER IF NOT EXISTS "$DB_USER"@'%' IDENTIFIED BY "$DB_PASSWORD";
GRANT ALL PRIVILEGES ON $DB_NAME.* TO "$DB_USER"@'%';
FLUSH PRIVILEGES;
EOF

   mysqladmin -u root -pTest123 shutdown

fi

echo "Went straight to exec"
exec mariadbd --user=mysql
