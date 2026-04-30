SHELL  := /bin/bash
.DEFAULT_GOAL := help

# DC sources .env at shell-execution time, ensuring docker compose always
# uses the values currently on disk — not stale values from the shell env.
DC  = set -a && . .env && set +a && docker compose
APP = $(DC) exec app

.PHONY: help setup _gen-secrets _fix-crlf build up down restart logs ps \
        shell migrate seed fresh assets assets-prod test install clean _ensure-db-user

# ---------------------------------------------------------------------------
# Help
# ---------------------------------------------------------------------------

help: ## Show available commands
	@printf "\n\033[1mSamarium ERP — Make Commands\033[0m\n\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-16s\033[0m %s\n", $$1, $$2}'
	@printf "\n"

# ---------------------------------------------------------------------------
# Setup — creates .env and generates secrets
# ---------------------------------------------------------------------------

setup: ## Create .env from .env.docker.example and generate missing secrets
	@if [ ! -f .env ]; then \
		cp .env.docker.example .env; \
		printf "  \033[32mcreated\033[0m  .env from .env.docker.example\n"; \
	else \
		printf "  \033[33mskipped\033[0m  .env already exists\n"; \
	fi
	@$(MAKE) --no-print-directory _fix-crlf
	@$(MAKE) --no-print-directory _gen-secrets
	@printf "  \033[32mdone\033[0m     edit .env to customise before running 'make install'\n\n"

_fix-crlf: # Strip Windows CRLF line endings that break env var parsing
	@sed -i 's/\r//' .env

_gen-secrets:
	@# APP_KEY
	@if grep -qE '^APP_KEY=[[:space:]]*$$' .env; then \
		KEY=$$(openssl rand -base64 32 | tr -d '\n'); \
		sed -i "s|^APP_KEY=.*|APP_KEY=base64:$$KEY|" .env; \
		printf "  \033[32mgenerated\033[0m APP_KEY\n"; \
	fi
	@# DB_USERNAME — set default if empty
	@if grep -qE '^DB_USERNAME=[[:space:]]*$$' .env; then \
		sed -i "s|^DB_USERNAME=.*|DB_USERNAME=samarium_user|" .env; \
		printf "  \033[32mdefaulted\033[0m DB_USERNAME=samarium_user\n"; \
	fi
	@# DB_PASSWORD
	@if grep -qE '^DB_PASSWORD=[[:space:]]*$$' .env; then \
		PASS=$$(openssl rand -hex 16); \
		sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$$PASS|" .env; \
		printf "  \033[32mgenerated\033[0m DB_PASSWORD\n"; \
	fi
	@# ADMIN_PASSWORD — generate if empty
	@if grep -qE '^ADMIN_PASSWORD=[[:space:]]*$$' .env; then \
		PASS=$$(openssl rand -hex 8); \
		sed -i "s|^ADMIN_PASSWORD=.*|ADMIN_PASSWORD=$$PASS|" .env; \
		printf "  \033[32mgenerated\033[0m ADMIN_PASSWORD\n"; \
	fi
	@# MYSQL_ROOT_PASSWORD
	@if grep -qE '^MYSQL_ROOT_PASSWORD=[[:space:]]*$$' .env; then \
		PASS=$$(openssl rand -hex 16); \
		sed -i "s|^MYSQL_ROOT_PASSWORD=.*|MYSQL_ROOT_PASSWORD=$$PASS|" .env; \
		printf "  \033[32mgenerated\033[0m MYSQL_ROOT_PASSWORD\n"; \
	elif ! grep -q '^MYSQL_ROOT_PASSWORD=' .env; then \
		PASS=$$(openssl rand -hex 16); \
		printf "\nMYSQL_ROOT_PASSWORD=$$PASS" >> .env; \
		printf "  \033[32mgenerated\033[0m MYSQL_ROOT_PASSWORD (appended)\n"; \
	fi

# ---------------------------------------------------------------------------
# Docker
# ---------------------------------------------------------------------------

build: ## Build (or rebuild) Docker images
	$(DC) build --no-cache

up: ## Start all containers in detached mode
	$(DC) up -d

down: ## Stop and remove containers
	$(DC) down

restart: ## Restart all containers
	$(DC) restart

logs: ## Stream container logs  (Ctrl+C to stop)
	$(DC) logs -f

ps: ## Show running containers and their status
	$(DC) ps

shell: ## Open a bash shell inside the app container
	$(APP) bash

# ---------------------------------------------------------------------------
# Laravel
# ---------------------------------------------------------------------------

migrate: ## Run database migrations
	$(APP) php artisan migrate

seed: ## Run database seeders
	$(APP) php artisan db:seed

fresh: ## Drop all tables, re-migrate and seed
	$(APP) php artisan migrate:fresh --seed

assets: ## Compile frontend assets (development)
	$(APP) npm run dev

assets-prod: ## Compile frontend assets (production / minified)
	$(APP) npm run production

test: ## Run PHPUnit test suite
	$(APP) php artisan test

# ---------------------------------------------------------------------------
# Composite workflows
# ---------------------------------------------------------------------------

_ensure-db-user: # Guarantee the app DB user exists via root — safe to re-run
	@printf "\n\033[1m  Ensuring database user...\033[0m\n"
	@set -a && . .env && set +a && \
		USR=$${DB_USERNAME:-samarium_user} && \
		DB=$${DB_DATABASE:-samarium} && \
		if docker compose exec -T db mysql -uroot -p"$$MYSQL_ROOT_PASSWORD" \
		  -e "CREATE DATABASE IF NOT EXISTS \`$$DB\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; \
		      CREATE USER IF NOT EXISTS '$$USR'@'%' IDENTIFIED BY '$$DB_PASSWORD'; \
		      GRANT ALL PRIVILEGES ON \`$$DB\`.* TO '$$USR'@'%'; \
		      FLUSH PRIVILEGES;" 2>/dev/null; then \
			printf "  \033[32mok\033[0m      user '$$USR' ready\n"; \
		else \
			printf "  \033[33mwarning\033[0m could not create user via root — MySQL may already be initialized correctly\n"; \
		fi

install: setup build ## Full first-run: secrets → build → up (healthy) → migrate → seed → assets
	@printf "\n\033[1m  Starting containers...\033[0m\n"
	@$(DC) up -d --wait
	@$(MAKE) --no-print-directory _ensure-db-user
	@printf "\n\033[1m  Running migrations...\033[0m\n"
	@$(APP) php artisan migrate --no-interaction
	@printf "\n\033[1m  Seeding database...\033[0m\n"
	@$(APP) php artisan db:seed --no-interaction
	@printf "\n\033[1m  Compiling assets...\033[0m\n"
	@$(APP) npm run dev
	@printf "\n\033[1;32m  Samarium is ready → http://localhost:8000\033[0m\n\n"

clean: ## Remove containers, networks and ALL volumes  [run: make clean CONFIRM=yes]
ifndef CONFIRM
	$(error Destructive action — run 'make clean CONFIRM=yes' to confirm)
endif
	docker compose down --remove-orphans
	docker volume rm samarium_dbdata samarium_vendor samarium_node_modules \
		samarium_laravel-storage samarium_laravel-cache 2>/dev/null || true
	@printf "  \033[32mdone\033[0m  all volumes removed\n"
