#!/bin/bash

mkdir -p /run/mysqld /var/lib/mysql
chown -R mysql:mysql /run/mysqld /var/lib/mysql

if [ ! -d /var/lib/mysql/mysql ]; then
    echo "Entry setup block"
    mysql_install_db --user=mysql --datadir=/var/lib/mysql
    echo "Installed db"
    mysqld_safe --datadir=/var/lib/mysql --port=3306 &
    sleep 3
    echo "Deamon launched"
    mysql_secure_installation <<EOF

n
y
$DB_PASSWORD
$DB_PASSWORD
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

   mysqladmin -u root -p$DB_PASSWORD shutdown

fi

echo "Went straight to exec"
exec mariadbd --user=mysql
