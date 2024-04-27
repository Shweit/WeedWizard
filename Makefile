php-cs-fixer:
	php vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php -v

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=-1

webpack-watch:
	npm build watch