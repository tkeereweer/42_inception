FROM debian:12.13-slim

RUN apt update && apt install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/* 

COPY conf/nginx.conf /etc/nginx/

COPY conf/inception.crt .

COPY --chmod=+x conf/run-nginx.sh .

EXPOSE 443

ENTRYPOINT ["./run-nginx.sh"]
