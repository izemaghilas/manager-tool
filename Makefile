.PHONY: up down

up:
	docker compose --env-file=".env.local" up -d

down:
	docker compose --env-file=".env.local" down

restart: down up

stop:
	docker compose --env-file=".env.local" stop

start:
	docker compose --env-file=".env.local" start
