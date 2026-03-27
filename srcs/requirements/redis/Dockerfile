FROM debian:12.13-slim

RUN apt update && apt install -y \
    redis-server \
    redis-cli \
    && rm -rf /var/lib/apt/lists/*

COPY conf/redis.conf /etc/redis/redis.conf

EXPOSE 6379

HEALTHCHECK --start-period=2s --interval=15s --timeout=15s --retries=3 \
    CMD redis-cli -h $REDIS_HOST -p 6379 ping || exit 1

ENTRYPOINT ["exec", "redis-server"]