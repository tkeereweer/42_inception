FROM debian:12.13-slim

RUN apt update && apt install -y \
    wget \
    curl \
    php-mysql \
    php-fpm\
    && rm -rf /var/lib/apt/lists/*

COPY --chmod=+x conf/build-wp.sh .

RUN ./build-wp.sh

COPY --chmod=+x conf/wp-config.php ./wordpress

COPY --chmod=+x conf/run-wp.sh .

EXPOSE 9000

ENTRYPOINT ["./run-wp.sh"]
