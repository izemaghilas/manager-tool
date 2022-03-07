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
#	docker exec -t manager_tool_app_container bin/console doctrine:database:drop --force --env=test || true
#	docker exec -t manager_tool_app_container bin/console doctrine:database:create --env=test
#	docker exec -t manager_tool_app_container php bin/console --env=test doctrine:schema:create --env=test 
#	docker exec -t manager_tool_app_container php bin/console doctrine:fixtures:load -n --env=test
	docker exec -t manager_tool_app_container php vendor/bin/phpunit --verbose --testsuite "Functional tests"
