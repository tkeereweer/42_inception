#!/bin/bash

if ! id "$FTP_USER" &>/dev/null; then
    useradd -d /var/www/html -s /bin/sh -M "$FTP_USER"
fi

echo "$FTP_USER:$FTP_PASS" | chpasswd

exec vsftpd