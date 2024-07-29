build:
	docker compose up --build

rebuild:
	docker compose down && docker compose up --build

start:
	docker compose up -d

restart:
	docker compose down && docker compose up -d

php:
	docker compose exec php bash

fixtures:
	docker compose exec php bash -c "php bin/console doctrine:fixtures:load"

migrations:
	docker compose exec php bash -c "php bin/console doctrine:fixtures:load"

seeder:
	docker compose exec php bash -c "php artisan db:seed --class=ParametrSeedr"
cs-fix:
	 docker compose exec php bash -c "tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src"
