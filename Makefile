
init: down build up

build: ## Build services.
    # We're creating this directory explicitly to make sure it'll have user's ownership, and not "root"
	mkdir -p ./docker_var/pgdata
	docker-compose build $(c)

up: ## Create and start services.
	# We're creating this directory explicitly to make sure it'll have user's ownership, and not "root"
	mkdir -p ./docker_var/pgdata
	docker-compose up -d $(c)

stop: ## Stop services.
	docker-compose stop $(c)

down: ## Stop and remove containers and volumes.
	docker-compose down -v $(c)

update-app:
	docker compose exec quiz-fpm composer install
	docker compose exec quiz-fpm /var/www/bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec quiz-fpm /var/www/bin/console app:fill-quiz

