.PHONY: start stop

start:
	docker compose --env-file=".env.local" up -d

stop:
	docker compose --env-file=".env.local" stop

restart: stop start

down:
	docker compose --env-file=".env.local" down
