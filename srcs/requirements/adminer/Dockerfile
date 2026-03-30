FROM debian:12.13-slim

RUN apt update && apt install -y \
    adminer \
    php \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

RUN ln -sf /usr/share/adminer/adminer.php /var/www/html/index.php

EXPOSE 8080

ENTRYPOINT ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]