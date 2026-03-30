FROM debian:12.13-slim

RUN apt update && apt install -y \
    vsftpd \
    && rm -rf /var/lib/apt/lists/*

COPY --chmod=+x tools/run-ftp.sh /

WORKDIR /var/www/html

COPY conf/vsftpd.conf /etc/vsftpd.conf

RUN mkdir -p /var/run/vsftpd/empty

EXPOSE 21 21000-21010

ENTRYPOINT ["/run-ftp.sh"]