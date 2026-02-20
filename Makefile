DOCKER_COMPOSE       = docker compose
DOCKER_EXEC_PHP      = docker exec yii2_php
DOCKER_EXEC_PHP_TTY  = docker exec -it yii2_php
DOCKER_EXEC_DB       = docker exec -i yii2_db

ifeq ("$(wildcard .env)","")
$(info .env not found, creating from .env.example...)
$(shell cp .env.example .env)
endif

include .env
export $(shell sed 's/=.*//' .env)

.PHONY: help install up down restart build logs logs-php logs-nginx shell migrate ps

.DEFAULT_GOAL := help

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Full setup: build images, install deps, start containers, run migrations
	@echo "==> Building Docker images..."
	$(DOCKER_COMPOSE) build
	@echo "==> Installing Composer dependencies..."
	$(DOCKER_COMPOSE) run --rm --no-deps php bash /app/docker/php/setup.sh
	@echo "==> Starting containers..."
	$(DOCKER_COMPOSE) up -d
	@echo "==> Waiting for MySQL to be ready..."
	@until $(DOCKER_EXEC_DB) mysqladmin ping -h localhost --silent 2>/dev/null; do \
		printf '.'; sleep 2; \
	done
	@echo ""
	@echo "==> Running migrations..."
	$(DOCKER_EXEC_PHP) php yii migrate --interactive=0
	@echo ""
	@echo "=========================================="
	@echo "  Application is ready!"
	@echo "  URL: http://localhost:${APP_PORT}"
	@echo "=========================================="

up: ## Start all containers
	$(DOCKER_COMPOSE) up -d

down: ## Stop and remove containers
	$(DOCKER_COMPOSE) down

restart: ## Restart all containers
	$(DOCKER_COMPOSE) restart

build: ## Rebuild Docker images
	$(DOCKER_COMPOSE) build

logs: ## Tail logs from all containers
	$(DOCKER_COMPOSE) logs -f

logs-php: ## Tail logs from PHP container only
	$(DOCKER_COMPOSE) logs -f php

logs-nginx: ## Tail logs from Nginx container only
	$(DOCKER_COMPOSE) logs -f nginx

shell: ## Open a bash shell in the PHP container
	$(DOCKER_EXEC_PHP_TTY) bash

migrate: ## Run database migrations
	$(DOCKER_EXEC_PHP) php yii migrate --interactive=0

ps: ## Show status of containers
	$(DOCKER_COMPOSE) ps