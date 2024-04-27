# WeedWizard Setup

## MySQL User
```mysql
mysql -u root -e "CREATE USER 'weedwizard'@'%' IDENTIFIED BY 'SicheresPasswort';"
mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'weedwizard'@'%';"
mysql -u root -e "FLUSH PRIVILEGES"
```

## Falls npm noch nicht installiert ist
Worüber ihr das installiert ist euch überlassen, ich empfehle aber das einfach über brew zu machen.

## Symfony server starten
```bash
composer install
npm install
symfony server:start
```