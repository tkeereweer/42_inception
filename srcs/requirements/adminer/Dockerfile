FROM debian:12.13-slim

RUN apt update && apt install -y \
    adminer \
    php \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 8080

ENTRYPOINT ["php", "-S", "localhost:8080", "-t", "/adminer"]