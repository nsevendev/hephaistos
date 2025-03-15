HEPH = heph-php
HEPH_DB = heph-db
HEPH_RABBIT = heph-rabbitmq
HEPH_WORKER = heph-worker

POSTGRES_USER = heph
POSTGRES_DB = heph

# Executables (local)
DOCKER_COMP = docker compose
DOCKER_COMP_PROD = docker compose -f compose.yaml -f compose.prod.yaml

# Docker containers
CONT_IT = $(DOCKER_COMP) exec -it
PHP_CONT = $(DOCKER_COMP) exec $(HEPH)

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
SYMFONY_TEST  = $(PHP) bin/phpunit

# Files env
ENV_FILE_DEV = .env.dev.local
ENV_FILE_PROD = .env.prod.local

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build dev prod start start-prod down logs sh composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker dev 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@echo "🚀 Construction des conteneurs dev -------------> START"
	@$(DOCKER_COMP) build --pull --no-cache
	@echo "✅ Construction des conteneurs dev -------------> END"

dev: ## Start the docker hub mode dev in detached mode (no logs)
	@echo "🚀 Demarrage des conteneurs dev -------------> START"
	@$(DOCKER_COMP) --env-file $(ENV_FILE_DEV) up --detach
	@echo "✅ Demarrage des conteneurs dev -------------> END"

## —— Docker generic 🐳 ————————————————————————————————————————————————————————————————
stop: ## Stop the docker hub
	@echo "🚀 Arret des conteneurs -------------> START"
	@$(DOCKER_COMP) down --remove-orphans
	@echo "✅ Arret des conteneurs -------------> END"

logs: ## Show live logs, pass the parameter "c=" to show logs of a specific container, api, db, worker, rabbit
	@$(eval c ?=api)
	@echo "🚀✅ Log du conteneur $(c) -------------> START OK"
	@$(DOCKER_COMP) logs -f $(if $(filter $(c),api),$(HEPH),$(if $(filter $(c),db),$(HEPH_DB),$(if $(filter $(c),worker),$(HEPH_WORKER),$(if $(filter $(c),rabbit),$(HEPH_RABBIT),$(error "❌ Valeur de c invalide : $(c)")))))

sh: ## Connect to the container, pass the parameter "c=" to connect to a specific container, api, db, worker, rabbit
	@$(eval c ?=api)
	@echo "🚀✅ Ouverture d'un shell dans le conteneur $(c) -------------> START OK"
	@$(CONT_IT) $(if $(filter $(c),api),$(HEPH),$(if $(filter $(c),db),$(HEPH_DB),$(if $(filter $(c),worker),$(HEPH_WORKER),$(if $(filter $(c),rabbit),$(HEPH_RABBIT),$(error "❌ Valeur de c invalide : $(c)"))))) bash

## —— Docker test 🐳 ————————————————————————————————————————————————————————————————
test: ## Start tests with paratest, pass the parameter "c=" to add options, example: make test c="tests/Unit"
	@$(eval c ?=)
	@echo "🚀 Start test -------------> START"
	@$(COMPOSER) test $(c)
	@echo "✅ Start test -------------> END"

coverage: ## Start tests with paratest with coverage, pass the parameter "c=" to add options to command, example: make test-cover c="--stop-on-failure"
	@$(eval c ?=)
	@echo "🚀 Start coverage -------------> START"
	@$(COMPOSER) test:cover $(c)
	@echo "✅ Start coverage -------------> END"

check: ## Start all process to check code quality, cs, phpstan, les tests, et normalise le fichier composer.json
	@echo "🚀 Start check -------------> START"
	@$(COMPOSER) check
	@echo "✅ Start check-------------> END"

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@echo "🚀 Start commande composer $(c) -------------> START"
	@$(COMPOSER) $(c)
	@echo "✅ Start commande composer $(c) -------------> END"

composer-arg: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(eval arg ?=)
	@echo "🚀 Start commande composer $(c) $(arg) -------------> START"
	@$(COMPOSER) $(c) $(arg)
	@echo "✅ Start commande composer $(c) $(arg) -------------> END"

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@echo "🚀 Start commande symfony $(c) -------------> START"
	@$(SYMFONY) $(c)
	@echo "✅ Start commande symfony $(c) -------------> END"

sf-test: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf-test c=about
	@$(eval c ?=)
	@$(SYMFONY_TEST) $(c)

cc: ## cache clear dev
	@$(MAKE) sf c="cache:clear"

cct: ## cache clear test
	@$(MAKE) sf c="cache:clear --env=test"

ccp: ## cache clear prod
	@$(MAKE) sf c="cache:clear --env=prod"

mm: ## Migrate the database
	@$(MAKE) sf c="doctrine:migrations:migrate"

mmt: ## Migrate the database test
	@$(MAKE) sf c="doctrine:migrations:migrate --env=test"

md: ## Generate a migration by comparing your current database to your mapping information
	@$(MAKE) sf c="doctrine:migrations:diff"

## —— Docker other 🐳 ————————————————————————————————————————————————————————————————
create-test-db: ## Create the test database (NOT USE by default, use sqlite for tests in cache)
	@echo "🚀 Creation base de donnees de test -------------> START"
	@$(CONT_IT) $(HEPH_DB) bash -c 'psql -U "$(POSTGRES_USER)" -d postgres -c "CREATE DATABASE $(POSTGRES_DB)_test OWNER = $(POSTGRES_USER);"'
	@echo "✅ Creation base de donnees de test -------------> END"

sh-db: ## Connect to the database container
	@echo "🚀✅ Start shell dans le conteneur de base de donnees -------------> START OK"
	@$(CONT_IT) $(HEPH_DB) psql -U $(POSTGRES_USER)

drop-migrate-test-db: ## drop and create database for tests
	@echo "🚀 Suppression et creation base de donnees de test -------------> START"
	@$(COMPOSER) migration-bdd-test
	@echo "✅ Suppression et creation base de donnees de test -------------> END"

## —— Docker prod 🐳 ————————————————————————————————————————————————————————————————
