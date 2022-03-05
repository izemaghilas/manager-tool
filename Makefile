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

# run functional tests 
f-tests:
	docker exec -t manager_tool_app_container php vendor/bin/phpunit --testsuite "Functional tests"
