FROM debian:12.13-slim

RUN apt update && apt install -y \
    vsftpd \
    && rm -rf /var/lib/apt/lists/*

COPY --chmod=+x tools/run-ftp.sh /

WORKDIR /var/www/html

COPY conf/vsftpd.conf /var/www/html

EXPOSE 21

ENTRYPOINT ["/run-ftp.sh"]