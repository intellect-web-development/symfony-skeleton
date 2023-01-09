init: docker-compose-override-init docker-down-clear docker-pull docker-build docker-up init-app phpmetrics
before-deploy: php-lint php-cs php-stan psalm test

up: docker-up
init-app: env-init composer-install database-create migrations-up fixtures
recreate-database: database-drop database-create

up-test-down: docker-compose-override-init docker-down-clear docker-pull docker-build docker-up env-init \
	composer-install database-create make-migration migrations-up before-deploy docker-down-clear

#up-test-down: docker-compose-override-init docker-down-clear docker-pull docker-build docker-up env-init \
#	composer-install database-create make-migration migrations-up fixtures before-deploy docker-down-clear

debug-router:
	docker compose run --rm app-php-cli bin/console debug:router

stub-composer-operation:
	docker compose run --rm app-php-cli composer require ...

docker-compose-override-init:
	cp docker-compose.override-example.yml docker-compose.override.yml

cache-clear:
	docker compose run --rm app-php-cli php bin/console cache:clear
	docker compose run --rm app-php-cli php bin/console cache:warmup

env-init:
	docker compose run --rm app-php-cli rm -f .env.local
	docker compose run --rm app-php-cli rm -f .env.test.local
	docker compose run --rm app-php-cli ln -sr .env.local.example .env.local
	docker compose run --rm app-php-cli ln -sr .env.test.local.example .env.test.local

fixtures:
	docker compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction
	docker compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction --env=test

make-migration:
	docker compose run --rm app-php-cli php bin/console make:migration

migrations-next:
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate next -n
	docker compose run --rm app-php-cli php bin/console --env=test doctrine:migrations:migrate next -n

migrations-prev:
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate prev -n
	docker compose run --rm app-php-cli php bin/console --env=test doctrine:migrations:migrate prev -n

migrations-up:
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction --env=test

migrations-down:
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate prev --no-interaction
	docker compose run --rm app-php-cli php bin/console doctrine:migrations:migrate prev --no-interaction --env=test

database-create:
	docker compose run --rm app-php-cli php bin/console doctrine:database:create --no-interaction --if-not-exists
	docker compose run --rm app-php-cli php bin/console doctrine:database:create --no-interaction --env=test --if-not-exists

database-drop:
	docker compose run --rm app-php-cli php bin/console doctrine:database:drop --force --no-interaction --if-exists
	docker compose run --rm app-php-cli php bin/console doctrine:database:drop --force --no-interaction --env=test --if-exists

test:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit

test-coverage:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

test-unit-coverage:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

test-integration-coverage:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=integration --coverage-clover var/clover.xml --coverage-html var/coverage

test-functional-coverage:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=functional --coverage-clover var/clover.xml --coverage-html var/coverage

test-unit:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=unit

test-functional:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=functional

test-integration:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=integration

test-acceptance:
	docker compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=acceptance

php-stan:
	docker compose run --rm app-php-cli ./vendor/bin/phpstan --memory-limit=-1

php-lint:
	docker compose run --rm app-php-cli ./vendor/bin/phplint

php-cs:
	docker compose run --rm app-php-cli ./vendor/bin/php-cs-fixer fix -v --using-cache=no
	docker compose run --rm app-php-cli ./vendor/bin/php-cs-fixer fix --dry-run --diff --using-cache=no

psalm:
	docker compose run --rm app-php-cli ./vendor/bin/psalm --no-cache $(ARGS)

composer-install:
	docker compose run --rm app-php-cli composer install

composer-dump:
	docker compose run --rm app-php-cli composer dump-autoload

composer-update:
	docker compose run --rm app-php-cli composer update

composer-outdated:
	docker compose run --rm app-php-cli composer outdated

composer-dry-run:
	docker compose run --rm app-php-cli composer update --dry-run

docker-up:
	docker compose up -d

docker-rebuild:
	docker compose down -v --remove-orphans
	docker compose up -d --build

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

phpmetrics:
	docker compose run --rm app-php-cli php ./vendor/bin/phpmetrics --report-html=var/myreport ./src
