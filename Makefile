BG_RED=\033[41m
BG_GREEN=\033[42m
BG_YELLOW=\033[43m
BG_BLUE=\033[44m
NC=\033[0m

php-cs-fixer:
	php vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php -v

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.dist.neon --memory-limit=-1

bearer:
	./bin/bearer scan ./

webpack-watch:
	npm run watch

test:
	php bin/phpunit

reset_db:
	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(BG_BLUE)     Datenbanken werden gelöscht...                               $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"
	@php bin/console doctrine:database:drop -q --force --env=dev || true
	@php bin/console doctrine:database:drop -q --force --env=test ||true

	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(BG_BLUE)     Datenbanken werden erstellt...                               $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"
	@php bin/console doctrine:database:create -q --env=dev || true
	@php bin/console doctrine:database:create -q --env=test || true

	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(BG_BLUE)     Migrationen werden ausgeführt...                             $(NC)"
	@echo "$(BG_BLUE)     (Ja, dass kann was länger dauern :) )                        $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"
	@php bin/console doctrine:migrations:migrate -q --no-interaction
	@php bin/console doctrine:migrations:migrate -q --no-interaction --env=test

	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(BG_BLUE)     Auf Entity Updates prüfen...                                 $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"

	@php bin/console make:migration --no-interaction -q
	@php bin/console doctrine:migrations:migrate -q --no-interaction
	@php bin/console doctrine:migrations:migrate -q --no-interaction --env=test

load-fixtures: reset_db
	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(BG_BLUE)     Fixtures laden...                                            $(NC)"
	@echo "$(BG_BLUE)     (Ja, dass kann was länger dauern :) )                        $(NC)"
	@echo "$(BG_BLUE)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"
	php bin/console doctrine:fixtures:load --env=dev --no-interaction

	@echo "$(NC)                                                                  $(NC)"
	@echo "$(BG_GREEN)                                                                  $(NC)"
	@echo "$(BG_GREEN)     Fertig!                                                      $(NC)"
	@echo "$(BG_GREEN)                                                                  $(NC)"
	@echo "$(NC)                                                                  $(NC)"
