# :euro: PRESTAWHALE :whale:

A Docker-compose stack to develop your Prestashop 1.6 App,on all Distribution (Linux, Mac, Windows).

1. [Version](#version)
2. [Prerequisite](#prerequisite)
3. [Installation](#installation)

***

## :floppy_disk: Version {#version}

- PrestaShop 1.6.1.24
- PHP 7.1
- MySql 5.7
- Adminer Latest

***

## :computer: Prerequisite {#prerequisite}

- Have docker and docker-compose on your VM (or on your desktop).

***

## :dvd: Installation {#installation}

1. Clone the project repository : `git clone [Project URL]`
2. Access to folder: `cd PrestaWhale/`
3. Modify `.env` to change your database password and username
4. Run command: `docker-compose up -d`
5. Connect to PrestaWhale-webserver container: `docker exec -it prestawhale-webserver /bin/bash`
6. Go in folder : `cd prestashop/`
7. Modify Access/Right of user : `chown www-data:www-data -R .`
8. Go on `localhost/install` to install your Store.
9. Get Ready to use PrestaShop

***

Created by [TheFury-BOY](adriendudeck.online) on MIT Licence :pencil2: 
