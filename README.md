# WeedWizard Setup

## MySQL User
```mysql
mysql -u root -e "CREATE USER 'weedwizard'@'%' IDENTIFIED BY 'SicheresPasswort';"
mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'weedwizard'@'%';"
mysql -u root -e "FLUSH PRIVILEGES"
```

## Symfony server starten
```bash
composer install
symfony server:start
```