FROM debian:12.13-slim

RUN apt update && apt install -y \
    adminer \
    php-mysql \
    php-fpm \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html/adminer

COPY --chmod=+x tools/run-adminer.sh .

COPY conf/www.conf /etc/php/8.2/fpm/pool.d/www.conf

RUN ln -sf /usr/share/adminer/adminer.php /var/www/html/adminer/index.php

EXPOSE 8080

ENTRYPOINT ["./run-adminer.sh"]
# ENTRYPOINT ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]
