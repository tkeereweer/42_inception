FROM debian:12.13-slim

RUN apt update && apt install -y \
    wget \
    php-fpm\
    && rm -rf /var/lib/apt/lists/*

WORKDIR /workspace

COPY conf/build-wp.sh .

RUN ./build-wp.sh

COPY conf/wp-conf.php ./wordpress

COPY conf/run-wp.sh .

EXPOSE 9000

ENTRYPOINT ["./run-wp.sh"]
