FROM debian:12.13-slim

RUN apt update && apt install -y \
    mariadb-server \
    mariadb-client \
    && rm -rf /var/lib/mysql/* \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /workspace

COPY conf/my.cnf /etc/mysql/my.cnf

COPY --chmod=+x tools/mariadb-conf.sh .

EXPOSE 3306

HEALTHCHECK --start-period=7s --interval=20s --timeout=15s --retries=3 \
    CMD mariadb -u $DB_USER -p$DB_PASSWORD --execute="SELECT 1;" || exit 1

ENTRYPOINT ["./mariadb-conf.sh"]