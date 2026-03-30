# Inception - User Documentation

## What is this project?

Inception is a self-contained web infrastructure running inside Docker containers. It hosts a WordPress website with a database, a cache layer, a database management tool, and an FTP server, all communicating over a private internal network.

---

## Services

| Service       | What it does                                                                          |
| ------------- | ------------------------------------------------------------------------------------- |
| **NGINX**     | The entry point. Handles HTTPS connections and forwards requests to WordPress.        |
| **WordPress** | The website itself, served via PHP-FPM.                                               |
| **MariaDB**   | The database that stores all WordPress content (posts, users, settings, etc.).        |
| **Redis**     | An in-memory cache that speeds up WordPress by storing frequently used data.          |
| **Adminer**   | A web-based interface for browsing and managing the database.                         |
| **FTP**       | Allows uploading and downloading files directly to/from the WordPress file directory. |

---

## Requirements

Before running the project you need:

- Docker] installed
- Docker Compose installed (included with Docker Desktop)
- A `.env` file in the `srcs/` directory (see [Configuration](#configuration) below)

---

## Configuration

All credentials and settings are stored in a `.env` file located at `srcs/.env`. This file is not included in the repository for security reasons. You must create it yourself before running the project.

A template with all the required fields is provided in `srcs/.env.example`. Copy it and fill in your values:

```bash
cp srcs/.env.example srcs/.env
```

The `.env` file contains:

| Variable       | Description                                                   |
| -------------- | ------------------------------------------------------------- |
| `DOMAIN_NAME`  | The domain name of the WordPress site (e.g. `mkeerewe.42.fr`) |
| `DB_NAME`      | Name of the WordPress database                                |
| `DB_USER`      | Database username used by WordPress                           |
| `DB_PASSWORD`  | Password for the database user                                |
| `DB_IP`        | Hostname of the MariaDB container (use `mariadb`)             |
| `DB_PORT`      | MariaDB port (use `3306`)                                     |
| `DB_HOST`      | Full database host string (use `mariadb:3306`)                |
| `WP_USER`      | WordPress admin username                                      |
| `WP_PASSWORD`  | WordPress admin password                                      |
| `WP_EMAIL`     | WordPress admin email address                                 |
| `REDIS_IP`     | Hostname of the Redis container (use `redis`)                 |
| `REDIS_PORT`   | Redis port (use `6379`)                                       |
| `REDIS_HOST`   | Full Redis host string (use `redis:6379`)                     |
| `FTP_USER`     | FTP login username                                            |
| `FTP_PASSWORD` | FTP login password                                            |

---

## Running the project

All commands are run from the root of the repository.

### Build the containers

Downloads and builds all Docker images. Run this once before starting for the first time, and again after any changes to a Dockerfile or configuration file.

```bash
make build
```

### Start the system

Starts all containers in the foreground (logs are visible in the terminal).

```bash
make up
```

To start in the background (detached mode):

```bash
make upd
```

### Stop the system

Stops all running containers but keeps the data volumes intact.

```bash
make stop
```

### Stop and remove containers

Stops and removes the containers. Data volumes are preserved, so WordPress content and the database are not lost.

```bash
make down
```

### Stop and remove everything (including data)

Removes containers **and** all volumes. This deletes all WordPress files and database data. Use with caution.

```bash
make vdown
```

### Check the status of the system

Shows which containers are running and their current state.

```bash
make check
```

---

## Accessing the services

> Before accessing any service, make sure your machine resolves the domain name to localhost. Add the following line to your `/etc/hosts` file if it is not already there:
>
> ```
> 127.0.0.1    mkeerewe.42.fr
> ```

| Service               | URL                                   | Notes                                                          |
| --------------------- | ------------------------------------- | -------------------------------------------------------------- |
| WordPress site        | `https://mkeerewe.42.fr`              | Main website                                                   |
| WordPress admin panel | `https://mkeerewe.42.fr/wp-login.php` | Log in with `WP_USER` / `WP_PASSWORD` from `.env`              |
| Adminer (database UI) | `http://mkeerewe.42.fr:8080`          | Connect to MariaDB using `DB_USER` / `DB_PASSWORD` from `.env` |

The site uses a self-signed SSL certificate, so your browser will show a security warning. This is expected, you can safely proceed.

### Connecting via FTP

You can connect to the WordPress file directory using any FTP client (e.g. FileZilla).

| Field    | Value                      |
| -------- | -------------------------- |
| Host     | `127.0.0.1`                |
| Port     | `21`                       |
| Username | `FTP_USER` from `.env`     |
| Password | `FTP_PASSWORD` from `.env` |

The FTP root directory is `/var/www/html`, which contains all WordPress files.

---

## Data persistence

WordPress files and the database are stored in Docker volumes, meaning they survive container restarts and `make down`. The volumes are:

- `wordpress-files` — WordPress site files
- `maria-db` — MariaDB database data

To completely reset the project to a clean state, run `make vdown` followed by `make build` and `make up`.

---

## Troubleshooting

**The site shows "Error establishing a database connection"**

- Make sure MariaDB has finished starting up. WordPress waits for it automatically, but a first boot can take a few seconds.
- Check that your `.env` file exists and has the correct values.
- Run `make check` to verify all containers are running.

**The browser shows a certificate warning**

- This is expected. The project uses a self-signed SSL certificate. Click "Advanced" and proceed to the site.

**A container keeps restarting**

- Run `docker compose -f srcs/docker-compose.yml logs <service-name>` to see the error output for that container.

**Changes to a config file are not taking effect**

- Rebuild the affected container with `make build`, then restart with `make up`.
