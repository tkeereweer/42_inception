# Inception - Developer Documentation

## Overview

Inception is a multi-container Docker infrastructure built around a WordPress site. Each service runs in its own container, all connected through a shared bridge network. There are no pre-built images, every container is built from a custom Dockerfile based on `debian:12.13-slim`.

**Services:**

- `nginx` — HTTPS reverse proxy (port 443)
- `wordpress` — PHP-FPM application server (port 9000, internal only)
- `mariadb` — MySQL-compatible database (port 3306, internal only)
- `redis` — In-memory object cache (port 6379, internal only)
- `adminer` — Web-based database management UI (port 8080)
- `ftp` — vsftpd file transfer server (port 21 + passive ports 21000-21010)

---

## Prerequisites

- Docker (Engine 20.10+)
- Docker Compose v2
- `make`
- An SSL certificate and private key (see [SSL Setup](#ssl-setup) below)

---

## Repository structure

```
inception/
├── Makefile                          # Convenience commands
├── secrets/
│   └── inception.key                 # SSL private key (not in git)
└── srcs/
    ├── .env                          # Environment variables (not in git)
    ├── .env.example                  # Template for .env
    ├── docker-compose.yml
    └── requirements/
        ├── nginx/
        │   ├── Dockerfile
        │   ├── conf/
        │   │   ├── nginx.conf
        │   │   └── inception.crt     # SSL certificate (not in git)
        │   └── tools/
        │       └── run-nginx.sh
        ├── wordpress/
        │   ├── Dockerfile
        │   ├── conf/
        │   │   └── www.conf          # PHP-FPM pool config
        │   └── tools/
        │       ├── build-wp.sh       # Downloads WP-CLI (runs at build time)
        │       └── run-wp.sh         # Sets up WordPress (runs at container start)
        ├── mariadb/
        │   ├── Dockerfile
        │   ├── conf/
        │   │   └── my.cnf
        │   └── tools/
        │       └── mariadb-conf.sh   # Initializes the database on first boot
        ├── redis/
        │   ├── Dockerfile
        │   ├── conf/
        │   │   └── redis.conf
        │   └── tools/
        │       └── run-redis.sh
        ├── adminer/
        │   ├── Dockerfile
        │   ├── conf/
        │   │   └── www.conf          # PHP-FPM pool config
        │   └── tools/
        │       └── run-adminer.sh
        └── ftp/
            ├── Dockerfile
            ├── conf/
            │   └── vsftpd.conf
            └── tools/
                └── run-ftp.sh
```

---

## Environment setup

### 1. Create the `.env` file

Copy the template and fill in your values:

```bash
cp srcs/.env.example srcs/.env
```

The `.env` file is loaded by Docker Compose and injected into every container as environment variables. All scripts and configuration files read credentials from these variables at runtime.

Full list of variables:

| Variable       | Example value       | Description                    |
| -------------- | ------------------- | ------------------------------ |
| `DOMAIN_NAME`  | `mkeerewe.42.fr`    | WordPress site URL             |
| `DB_NAME`      | `wordpress_db`      | Database name                  |
| `DB_USER`      | `wp_user`           | Database username              |
| `DB_PASSWORD`  | `somepassword`      | Database password              |
| `DB_IP`        | `mariadb`           | MariaDB container hostname     |
| `DB_PORT`      | `3306`              | MariaDB port                   |
| `DB_HOST`      | `mariadb:3306`      | Full host string for WordPress |
| `WP_USER`      | `admin`             | WordPress admin username       |
| `WP_PASSWORD`  | `somepassword`      | WordPress admin password       |
| `WP_EMAIL`     | `admin@example.com` | WordPress admin email          |
| `REDIS_IP`     | `redis`             | Redis container hostname       |
| `REDIS_PORT`   | `6379`              | Redis port                     |
| `REDIS_HOST`   | `redis:6379`        | Full host string for WordPress |
| `FTP_USER`     | `ftpuser`           | FTP login username             |
| `FTP_PASSWORD` | `somepassword`      | FTP login password             |

### 2. SSL setup

The project requires an SSL certificate and private key. They are intentionally excluded from git.

Generate a self-signed certificate valid for your domain:

```bash
openssl req -newkey rsa:4096 -x509 -sha256 -days 3650 -nodes \
    -out srcs/requirements/nginx/conf/inception.crt \
    -keyout secrets/inception.key \
    -subj "/C=<country_code>/ST=<state>/L=<city>/O=<org>/OU=<org_unit>/CN=<domain>"
```

- The private key goes in `secrets/inception.key`. It is mounted into the nginx container as a Docker secret at `/run/secrets/ssl-key`.
- The certificate goes in `srcs/requirements/nginx/conf/inception.crt`. It is copied into the nginx image at build time.

### 3. Add the domain to `/etc/hosts`

Services are accessed via the domain name, not `localhost`. Add this line to your `/etc/hosts`:

```
127.0.0.1    mkeerewe.42.fr
```

---

## Building and running

All commands are run from the root of the repository.

```bash
make build    # Build all images
make up       # Start in foreground (logs visible)
make upd      # Start in background (detached)
make stop     # Stop containers, keep volumes
make down     # Remove containers, keep volumes
make vdown    # Remove containers AND volumes (full reset)
make check    # Show container status
```

On first boot, `make build` followed by `make up` is all that is needed. The containers initialize themselves automatically.

---

## How each service initializes

### MariaDB

`mariadb-conf.sh` runs on container start. It checks whether `/var/lib/mysql/mysql` exists:

- **First boot:** Runs `mysql_install_db`, starts a temporary daemon, runs `mysql_secure_installation`, creates the database and user, then restarts as the main process.
- **Subsequent boots:** Skips initialization and goes straight to `exec mariadbd`.

A `HEALTHCHECK` is defined in the Dockerfile. WordPress and Adminer use `condition: service_healthy` to wait for MariaDB before starting.

### WordPress

`run-wp.sh` runs on container start. It checks whether `/var/www/html/wp-load.php` exists:

- **First boot:** Downloads WordPress core with WP-CLI, generates `wp-config.php` with database and Redis credentials, installs WordPress, creates a second user, installs and activates the Redis Cache plugin.
- **Subsequent boots:** Skips setup and goes straight to starting PHP-FPM.

WordPress depends on both MariaDB and Redis being healthy before it starts.

> **Note:** PHP-FPM clears environment variables by default. `clear_env = no` is set in `www.conf` so that `getenv()` calls in PHP can read the container's environment variables.

### Redis

Starts immediately with the config from `redis.conf`. A `HEALTHCHECK` pings the Redis server to confirm it is ready.

### NGINX

Starts after WordPress is running. Reads the SSL certificate from the image and the private key from the Docker secret at `/run/secrets/ssl-key`. Forwards `.php` requests to `wordpress:9000` via FastCGI.

### Adminer

Starts after MariaDB is healthy. Runs PHP-FPM serving `adminer.php` on port 8080.

### FTP

Creates the FTP user defined in `FTP_USER` if it does not exist, sets the password, and starts vsftpd. The working directory is chrooted to `/var/www/html` (the `wordpress-files` volume).

---

## Data persistence

Two named Docker volumes are used:

| Volume            | Mounted in            | Contains                                           |
| ----------------- | --------------------- | -------------------------------------------------- |
| `wordpress-files` | nginx, wordpress, ftp | All WordPress core files, themes, plugins, uploads |
| `maria-db`        | mariadb               | MariaDB data directory (`/var/lib/mysql`)          |

Volumes persist across `make down` and container restarts. Running `make vdown` removes both volumes, which means all WordPress content and database data will be lost.

On a Linux host, Docker stores named volumes at:

```
/var/lib/docker/volumes/
```

but it can be configured to store them elsewhere.

---

## Useful commands

View logs for a specific service:

```bash
docker compose -f srcs/docker-compose.yml logs -f <service>
# e.g: docker compose -f srcs/docker-compose.yml logs -f wordpress
```

Open a shell inside a running container:

```bash
docker compose -f srcs/docker-compose.yml exec <service> bash
# e.g: docker compose -f srcs/docker-compose.yml exec mariadb bash
```

Connect to the MariaDB database directly:

```bash
docker compose -f srcs/docker-compose.yml exec mariadb \
    mariadb -u $DB_USER -p$DB_PASSWORD $DB_NAME
```

Rebuild a single service after changing its Dockerfile or config:

```bash
docker compose -f srcs/docker-compose.yml build <service>
docker compose -f srcs/docker-compose.yml up -d <service>
```

---

## Networking

All containers are on a single Docker bridge network named `my-network`. Containers address each other by service name (e.g. `mariadb`, `redis`, `wordpress`). No container is reachable from outside the host except through the explicitly mapped ports:

| Port          | Service            |
| ------------- | ------------------ |
| `443`         | nginx (HTTPS)      |
| `8080`        | adminer            |
| `21`          | ftp (control)      |
| `21000-21010` | ftp (passive data) |

WordPress and MariaDB intentionally have no exposed ports in production — they are only reachable from within the Docker network.
