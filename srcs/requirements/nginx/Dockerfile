FROM alpine:3.22.3
WORKDIR /workspace

RUN apk update && apk add --no-cache nginx

COPY conf/nginx.conf /etc/nginx/

EXPOSE 443

ENTRYPOINT ["rc-service", "nginx", "start"]
