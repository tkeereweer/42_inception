FROM debian:12.13-slim

RUN apt update && apt install -y \
    curl \
    mariadb-client \
    php-mysql \
    php-fpm\
    && rm -rf /var/lib/apt/lists/*

COPY --chmod=+x conf/build-wp.sh .

RUN ./build-wp.sh

COPY --chmod=+x conf/wp-config.php .

COPY --chmod=+x conf/run-wp.sh .

COPY conf/www.conf /etc/php/8.2/fpm/pool.d/www.conf

EXPOSE 9000

ENTRYPOINT ["./run-wp.sh"]
