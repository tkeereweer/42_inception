FROM debian:12.13-slim

RUN apt update && apt install -y \
    mariadb-server \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /workspace

COPY conf/my.cnf /etc/mysql/my.cnf

COPY --chmod=+x tools/mariadb-conf.sh .

EXPOSE 3306

HEALTHCHECK --start-period=3s --interval=15s --timeout=15s --retries=2 \
    CMD mariadb -u root --execute="SELECT 1;" || exit 1

ENTRYPOINT ["./mariadb-conf.sh"]