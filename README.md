# WeedWizard Setup

## MySQL User
In order to run the local development server, you need to create a MySQL user with the following credentials:
```mysql
mysql -u root -e "CREATE USER 'weedwizard'@'%' IDENTIFIED BY 'SicheresPasswort';"
mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'weedwizard'@'%';"
mysql -u root -e "FLUSH PRIVILEGES"
```
The mysql user is needed, so that the application can communicate with the database. If you choose to use another database
system, you need to adjust the .env file accordingly. If you do so, please make sure to add the .env file to your local 
.gitignore, so that it does not get pushed to the repository.

## Install NVM / NPM
We need to install NVM and NPM to run the frontend build process. Follow these steps to install NVM and NPM:
```bash
brew install nvm
source $(brew --prefix nvm)/nvm.sh
nvm install 20
nvm use 20
nvm alias default 20
```
If problems occur, please refer to the following NVM documentation: https://medium.com/@priscillashamin/how-to-install-and-configure-nvm-on-mac-os-43e3366c75a6

If you do not have Homebrew installed, please refer to the following documentation: https://brew.sh/

## Install Project dependencies
Before installing the project dependencies, make sure you have composer and npm installed. If you do not have composer installed, please refer to the following documentation: https://getcomposer.org/download/
```bash
composer install
npm install
```

## Run a Webpack Encore instance
```bash
npm run watch
```


## Symfony server starten
```bash
symfony server:start
```

## Für den Tile-Server
```bash
brew install postgis
brew services start postgresql@14
createdb $(whoami)
psql -c "CREATE ROLE weedwizard WITH LOGIN PASSWORD 'weedwizard';"
psql -c "GRANT pg_write_server_files TO weedwizard;"
psql -c "CREATE DATABASE weedwizard_geometry;"
psql -c "ALTER DATABASE weedwizard_geometry OWNER TO weedwizard;"
psql -d weedwizard_geometry -c "ALTER TABLE weedwizard_geometry OWNER TO weedwizard;"
psql -c "CREATE EXTENSION postgis;"
psql -c "CREATE EXTENSION postgis_topology;"
psql -U weedwizard -d weedwizard_geometry -c "CREATE TABLE weedwizard_geometry (id SERIAL PRIMARY KEY, geom GEOMETRY NOT NULL, src text);"
brew services stop postgresql@14
```