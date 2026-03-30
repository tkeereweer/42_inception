_This project has been created as part of the 42 curriculum by mkeerewe_

# Inception

## Description

The goal of this project is to set up a system composed of multiple containers that communicate with each other over a network and persist their data in Docker volumes.
The system has one container running a NGINX server that listens on port 443 for client requests. It then works as a reverse proxy and passes the requests to a Wordpress container on port 9000. The Wordpress container is connected to a container running MariaDB. All data is persisted in Docker volumes.
In addition to this essential infrastructure, a container running a Redis cache has been configured to off load some Wordpress requests to MariaDB. For easier database management, adminer is running in a separate container and can be accessed at mkeerewe.42.fr/adminer/. Files can be uploaded and downloaded to Wordpress through an FTP server.

## Architecture

```
                        ┌─────────────────────────────────────────────────────┐
                        │                   my-network (bridge)               │
                        │                                                     │
  HTTPS :443            │  ┌─────────┐    FastCGI     ┌───────────┐           │
Client ──────────────►  │  │  NGINX  │ ────────────►  │ WordPress │           │
                        │  └─────────┘  :9000         │ (PHP-FPM) │           │
                        │       │                     └───────────┘           │
                        │       │ static files             │  │               │
                        │       │                   SQL :3306  Redis :6379    │
                        │       ▼                       ▼        ▼            │
                        │  ┌────────────────┐     ┌─────────┐  ┌───────┐      │
                        │  │ wordpress-files│     │ MariaDB │  │ Redis │      │
                        │  │    (volume)    │     └─────────┘  └───────┘      │
                        │  └────────────────┘         │                       │
                        │       ▲                 ┌────────┐                  │
  FTP :21               │       │                 │maria-db│                  │
Client ──────────────►  │  ┌─────────┐            │(volume)│                  │
                        │  │   FTP   │            └────────┘                  │
  HTTP :8080            │  └─────────┘                                        │
Client ──────────────►  │  ┌─────────┐                                        │
                        │  │Adminer  │                                        │
                        │  └─────────┘                                        │
                        └─────────────────────────────────────────────────────┘
```

## Key Concepts

### Virtual Machines vs. Containers (and Docker)

Both virtual machines (VM) and containers allow you to create isolated environments within your host machine. They differ in that VMs virtualise an entire operating system while containers sit on top of the host's operating system, share the host's kernel, and contain just the application and all its dependancies. This makes containers more lightweight than VMs and ideal to easily share applications among hosts.
Docker is a platform used to build container images, run them on a host machine, and manage their networking, storage, and lifecycle.

### Secrets vs. Environment Variables

Secrets and environment variables are both ways to pass confidential information (e.g. password, keys) to an application without exposing them in the application code. The main difference between secrets and environment variables is that environment variables are accessible to all processes running within that environment and can leak through tools like docker inspect or child processes, while secrets are accessible only to the process to which they are mounted.

### Docker Network vs Host Network

Docker networks are used to create a complete virtual network for containers to communicate with each other. It comes with a full range of IP addresses and ports. On a Docker network, containers can be identified by their service name. With a host network, the container shares the host's network stack directly, meaning it uses the same IP address and ports as the host with no network isolation.

### Docker Volumes vs. Bind Mounts

By their nature, containers do not persist any data after they terminate so all modifications made at container runtime are lost. Docker volumes and bind mounts are both ways to persist data after a container has stopped. Docker volumes are completly managed by Docker and the storage path is determined by Docker rather than the user. Bind mounts are directories from the host system that are mounted on the container's filesystem. Modification made by the containerised application will therefore be accessible to the host.

## Security

Several deliberate decisions were made to reduce the attack surface of the infrastructure:

- **No credentials in image layers:** all passwords and usernames are injected at runtime via environment variables, so they never appear in the image build history or the source code.
- **Docker secret for the SSL private key:** the private key is mounted into the nginx container as a Docker secret (`/run/secrets/ssl-key`) rather than passed as an environment variable, limiting its visibility to the nginx process only.
- **Unexposed internal ports:** MariaDB (3306) and WordPress/PHP-FPM (9000) have no ports mapped to the host. They are only reachable from within the Docker network, meaning the database and application server are never directly accessible from outside the host.
- **TLS 1.2 and 1.3 only:** older, vulnerable SSL/TLS versions (SSLv3, TLS 1.0, TLS 1.1) are explicitly disabled in the nginx configuration.
- **FTP user is chrooted:** the FTP user's filesystem access is restricted to `/var/www/html`, preventing navigation outside the WordPress directory.

## Design Tradeoffs

### Single shared network

All containers are connected to one bridge network. A more secure design would use two separate networks, a frontend network (nginx and wordpress) and a backend network (wordpress and mariadb), so that nginx has no direct route to the database. Using a single network is simpler to configure and sufficient for a project of this scale, but it sacrifices some network-level isolation between services.

### Runtime initialisation

Both WordPress and MariaDB initialise themselves on first container start rather than at image build time. This means credentials are never baked into an image layer and the same image can be configured for different environments just by changing the `.env` file. The tradeoff is that the first boot is slower and the containers depend on the `.env` file being present and correct at startup.

### Startup dependencies with healthchecks

WordPress waits for MariaDB and Redis to be fully ready before starting, using Docker's `condition: service_healthy`. This prevents startup failures caused by WordPress trying to connect to a database that is still initialising. The tradeoff is a slower first boot, since WordPress cannot start until both healthchecks pass. Without this, a simple `depends_on` would only wait for the container to start, not for the service inside it to be ready.

## Instructions

1. Clone the repository
2. Generate an SSL certificate and private key (see DEV_DOC.md)
3. Create `srcs/.env` from the provided `srcs/.env.example` template
4. Run `make build` then `make up`

For full setup and usage details see [DEV_DOC.md](DEV_DOC.md) and [USER_DOC.md](USER_DOC.md).

## Resources

| Service                | Documentation                                                                                                                      |
| ---------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| Docker                 | [docs.docker.com](https://docs.docker.com/)                                                                                        |
| MariaDB                | [MariaDB Quickstart Guide](https://mariadb.com/docs/server/mariadb-quickstart-guides/installing-mariadb-server-guide)              |
| WordPress              | [WordPress Installation Guide](https://developer.wordpress.org/advanced-administration/before-install/howto-install/)              |
| WP-CLI                 | [WP-CLI Command Reference](https://developer.wordpress.org/cli/commands/)                                                          |
| WordPress Redis Plugin | [WP Redis Plugin](https://wordpress.org/plugins/wp-redis/)                                                                         |
| Nginx                  | [Nginx Beginner's Guide](https://nginx.org/en/docs/beginners_guide.html)                                                           |
| Adminer                | [Debian Adminer Wiki](https://wiki.debian.org/adminer)                                                                             |
| Redis                  | [Install Redis on Linux](https://redis.io/docs/latest/operate/oss_and_stack/install/archive/install-redis/install-redis-on-linux/) |
| FTP                    | [Debian FTP Wiki](https://wiki.debian.org/FTP)                                                                                     |
