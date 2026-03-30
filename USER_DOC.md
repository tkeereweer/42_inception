# Inception - USER_DOC

## Services
- NGINX server working as reverse proxy for a Wordpress site
- Wordpress website
- MariaDB as the database for the Wordpress website
- Redis as a cache for the Wordpress
- Adminer for database management
- An FTP server to download and upload files to the Wordpress website

## Commands
### Building the containers
To build the containers run ```make build```

### Starting the system
To start up the system run ```make up```

### Shutting down the system
To shut down the system run ```make down```

### Checking the system
To check that the system is running run ```make check```

## Website access and administration
To access the website go the mkeerewe.42.fr
The admin panal can be found at mkeerewe.42.fr/wp-login.php

## Credentials
The login info for all the services is stored in a .env file
Admin user for Wordpress: WP_USER
Password: WP_PASSWORD

This file should always be present to be able to run the system. The stucture of the .env file can be found the .env.example file.
