# =============================================================================
# Laravel + Docker Compose Development Makefile
# =============================================================================
# Usage: make <target>
# Run `make` or `make help` to see all available commands.
# =============================================================================

.DEFAULT_GOAL := help

# Load .env variables for DB credentials
ifneq (,$(wildcard ./.env))
    include .env
    export
endif

# Defaults
SERVICE ?= app
DB_DATABASE ?= zendesk
DB_USERNAME ?= postgres

# Docker Compose shorthand
DC = docker compose
EXEC = $(DC) exec
EXEC_APP = $(EXEC) app
ARTISAN = $(EXEC_APP) php artisan
COMPOSER_CMD = $(EXEC_APP) composer
EXEC_NODE = $(EXEC) node

# =============================================================================
# Help
# =============================================================================

.PHONY: help
help: ## Show this help message
	@echo ""
	@echo "  Laravel Docker Compose — Available Commands"
	@echo "  ============================================"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'
	@echo ""

# =============================================================================
# Container Lifecycle
# =============================================================================

.PHONY: up down restart build rebuild ps logs

up: ## Start all containers in detached mode
	$(DC) up -d

down: ## Stop and remove all containers
	$(DC) down

restart: ## Restart all containers (down + up)
	@$(MAKE) down
	@$(MAKE) up

build: ## Build all images from scratch (no cache)
	$(DC) build --no-cache

rebuild: ## Full clean rebuild (down + build + up)
	@$(MAKE) down
	@$(MAKE) build
	@$(MAKE) up

ps: ## Show status of all containers
	$(DC) ps

logs: ## Tail container logs (default: app, override with service=<name>)
	$(DC) logs -f $(SERVICE)

# =============================================================================
# Shell Access
# =============================================================================

.PHONY: shell shell-queue shell-node pgsql-shell redis-cli

shell: ## Open a bash shell in the app container
	$(EXEC_APP) bash

shell-queue: ## Open a bash shell in the queue container
	$(EXEC) queue bash

shell-node: ## Open an sh shell in the node container (Alpine)
	$(EXEC_NODE) sh

pgsql-shell: ## Open a psql session in the pgsql container
	$(EXEC) pgsql psql -U $(DB_USERNAME) -d $(DB_DATABASE)

redis-cli: ## Open a redis-cli session in the redis container
	$(EXEC) redis redis-cli

# =============================================================================
# Artisan Shortcuts
# =============================================================================

.PHONY: migrate migrate-fresh rollback seed tinker cache-clear config-cache
.PHONY: route-list queue-work queue-restart storage-link key-generate art

migrate: ## Run database migrations
	$(ARTISAN) migrate

migrate-fresh: ## Drop all tables and re-run migrations with seeding (DESTRUCTIVE)
	@echo "\033[31m⚠  WARNING: This will DROP ALL TABLES and re-seed the database!\033[0m"
	@echo "   Press Ctrl+C within 3 seconds to abort..."
	@sleep 3
	$(ARTISAN) migrate:fresh --seed

rollback: ## Rollback the last database migration batch
	$(ARTISAN) migrate:rollback

seed: ## Run database seeders
	$(ARTISAN) db:seed

tinker: ## Open Laravel Tinker REPL
	$(ARTISAN) tinker

cache-clear: ## Clear all caches (optimize:clear)
	$(ARTISAN) optimize:clear

config-cache: ## Cache the configuration files
	$(ARTISAN) config:cache

route-list: ## List all registered routes
	$(ARTISAN) route:list

queue-work: ## Start processing queued jobs
	$(ARTISAN) queue:work

queue-restart: ## Restart the queue worker daemon
	$(ARTISAN) queue:restart

storage-link: ## Create the public storage symlink
	$(ARTISAN) storage:link

key-generate: ## Generate a new application key
	$(ARTISAN) key:generate

art: ## Run any artisan command — usage: make art cmd="make:controller FooController"
	$(ARTISAN) $(cmd)

# =============================================================================
# Composer
# =============================================================================

.PHONY: composer-install composer-update composer

composer-install: ## Run composer install
	$(COMPOSER_CMD) install

composer-update: ## Run composer update
	$(COMPOSER_CMD) update

composer: ## Run any composer command — usage: make composer cmd="require spatie/laravel-permission"
	$(COMPOSER_CMD) $(cmd)

# =============================================================================
# NPM / Frontend (via node container)
# =============================================================================

.PHONY: npm-install npm-dev npm-build npm

npm-install: ## Run npm install in the node container
	$(EXEC_NODE) npm install

npm-dev: ## Run npm run dev (Vite dev server)
	$(EXEC_NODE) npm run dev

npm-build: ## Run npm run build (Vite production build)
	$(EXEC_NODE) npm run build

npm: ## Run any npm command — usage: make npm cmd="run dev"
	$(EXEC_NODE) npm $(cmd)

# =============================================================================
# Testing & Quality
# =============================================================================

.PHONY: test test-filter pint stan

test: ## Run the test suite (php artisan test)
	$(ARTISAN) test

test-filter: ## Run a specific test — usage: make test-filter name=UserTest
	$(ARTISAN) test --filter=$(name)

pint: ## Run Laravel Pint code formatter
	$(EXEC_APP) ./vendor/bin/pint

stan: ## Run PHPStan / Larastan static analysis (level 7)
	$(EXEC_APP) ./vendor/bin/phpstan analyse

# =============================================================================
# Database
# =============================================================================

.PHONY: fresh-seed db-backup

fresh-seed: ## Alias for migrate-fresh (drop all + migrate + seed)
	@$(MAKE) migrate-fresh

db-backup: ## Dump the database to a timestamped .sql file in ./backups
	@mkdir -p backups
	$(EXEC) pgsql pg_dump -U $(DB_USERNAME) $(DB_DATABASE) > backups/$(DB_DATABASE)_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "\033[32m✓ Backup saved to backups/\033[0m"

# =============================================================================
# Tenancy (stancl/tenancy v3)
# =============================================================================

.PHONY: tenants-list tenants-migrate tenant-run

tenants-list: ## List all registered tenants
	$(ARTISAN) tenants:list

tenants-migrate: ## Run migrations for all tenants
	$(ARTISAN) tenants:migrate

tenant-run: ## Run an artisan command for tenant(s) — usage: make tenant-run tenant=<id> cmd="db:seed"
	$(ARTISAN) tenants:run $(cmd) --tenants=$(tenant)
