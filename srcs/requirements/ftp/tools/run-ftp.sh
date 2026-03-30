#!/bin/bash

usermod -d /var/www/html -s /bin/sh "$FTP_USER"

exec vsftpd