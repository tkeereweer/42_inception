FROM debian:12.13-slim

RUN apt update && apt install -y \
    wget \
    php-fpm\
    && rm -rf /var/lib/apt/lists/*

WORKDIR /workspace
