*This project has been created as part of the 42 curriculum by mkeerewe*
# Inception

## Description
The goal of this project is to set up a system composed of multiple containers that communicate with each other over a netword and persist their data in Docker volumes.
The system has one container running a NGINX server that listens on port 443 for client requests. It then works as a reverse proxy and passes the requests to a Wordpress container on port 9000. The Wordpress container is connected to a container running MariaDB. All data is presisted in Docker volumes.
In addition to this essential infrastructure, a container running a Redis cache has been configured to off load some Wordpress requests to MariaDB. For easier database management, adminer is running in a seperate container and can be accessed at mkeerewe.42.fr/adminer/. Files can be uploaded and downloaded to Wordpress through an FTP server.

### Virtual Machines vs. Containers (and Docker)
Both virtual machines (VM) and containers allow you to create isolated environments within your host machine. They differ in that VMs virtualise an entire operating system while containers sit on top of the host's operating system and contain just the application and all its dependancies. This makes containers more lightweight than VMs and ideal to easily share applications among hosts.
Docker is a container runtime that is used to build container images and run them on a host machine.

### Secrets vs. Environment Variables
Secrets and environment variables are both ways to pass confidential information (e.g. password, keys) to an application without exposing them in the application code. The main difference between secrets and environment variables is that environment variable are accessible to all processes running within that environment while secrets are accessible only to the process to which they are mounted.

### Docker Network vs Host Network
Docker networks are used to create a complete virtual network for containers to communicate with each other. It comes with a full range of IP addresses and ports. On a Docker network, containers can be identified by their service name.

### Docker Volumes vs. Bind Mounts
By their nature, containers do not persist any data after they terminate so all modifications made at container runtime are lost. Docker volumes and bind mounts are both ways to persist data after a container has stopped. Docker volumes are completly managed by Docker and are not meant to be accessed from the host. Bind mounts are directories from the host system that are mounted on the container's filesystem. Modification made by the containerised application will therefore be accessible to the host.

## Instuctions
- How to run the project

## Resources
https://docs.docker.com/
https://mariadb.com/docs/server/mariadb-quickstart-guides/installing-mariadb-server-guide
https://developer.wordpress.org/advanced-administration/before-install/howto-install/
https://developer.wordpress.org/cli/commands/
https://nginx.org/en/docs/beginners_guide.html
https://wiki.debian.org/adminer
https://redis.io/docs/latest/operate/oss_and_stack/install/archive/install-redis/install-redis-on-linux/
https://wordpress.org/plugins/wp-redis/
https://wiki.debian.org/FTP
