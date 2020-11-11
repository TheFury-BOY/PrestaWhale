# PRESTAWHALE

## Version

- PrestaShop 1.6.1.9
- PHP 7.1
- MySql 8.0

## Prés-requis

- Have docker and docker-compose on your VM (or on your desktop).

## Installation

1. Clone the project repository : `git clone [url du projet]`
2. Access to folder: `cd PrestaWhale/`
3. Run command: `docker-compose up -d`
4. Connect to PrestaWhale-webserver container: `docker exec -it prestawhale-webserver /bin/bash`
5. Go in folder : `cd prestashop/`
6. Modify Access/Right of user : `chown www-data:www-data -R .`
7. Go on `localhost/install` to install your Store.
8. Uncomment security in PrestaWhale/config/php/php.ini
9. restart your docker-compose stack with `docker-compose restart`
