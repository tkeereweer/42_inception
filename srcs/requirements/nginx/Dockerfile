FROM debian:12.13-slim

RUN apt update && apt install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/* 

COPY conf/nginx.conf /etc/nginx/

WORKDIR /workspace

EXPOSE 443

ENTRYPOINT ["rc-service", "nginx", "start"]
