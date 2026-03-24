FROM debian:12.13-slim

RUN apt update && apt install -y \
    mariadb-server \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /workspace

COPY conf/my.cnf /etc/mysql/my.cnf

COPY --chmod=+x conf/mariadb-conf.sh .

EXPOSE 3306

ENTRYPOINT ["./mariadb-conf.sh"]