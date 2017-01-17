# ----------------
# Make help script
# ----------------

# Usage:
# Add help text after target name starting with '\#\#'
# A category can be added with @category. Team defaults:
# 	dev-environment
# 	docker
# 	drush
# 	test

# Output colors
GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

# Script
HELP_FUN = \
	%help; \
	while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
	print "usage: make [target]\n\n"; \
	print "see makefile for additional commands\n\n"; \
	for (sort keys %help) { \
	print "${WHITE}$$_:${RESET}\n"; \
	for (@{$$help{$$_}}) { \
	$$sep = " " x (32 - length $$_->[0]); \
	print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
	}; \
	print "\n"; }

help: ## Show help (same if no target is specified).
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

.PHONY: clean install test down up wip behat phpunit pull-db pull-logs logs

#
# Dev-Environment
#
install:	##@dev-environment Build development environment from scratch.
	if [ ! -f docker-compose.local.yml ]; then cp docker-compose.local.example.yml docker-compose.local.yml; fi
	if [[ $$(docker-compose ps -q) ]]; then docker-compose -f docker-compose.yml -f docker-compose.local.yml down; fi
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d --build --force-recreate
	make pull-db
	make pull-logs
	docker exec -u www-data squabble_app_1 php artisan migrate
	docker-compose ps

pull-logs:	##@dev-environment Download AND import `lumen.log`.
	docker exec squabble_app_1 sh -c "if [ -f /var/www/html/storage/logs/lumen.log]; then rm /var/www/html/storage/logs/lumen.log; fi"
	docker exec -u www-data squabble_app_1 touch /var/www/html/storage/logs/lumen.log

pull-db:	##@dev-environment Download AND import `database.sqlite`.
	docker exec squabble_app_1 sh -c "if [ -f /var/www/html/storage/database.sqlite ]; then rm /var/www/html/storage/database.sqlite; fi"
	docker exec -u www-data squabble_app_1 touch /var/www/html/storage/database.sqlite
	docker exec -u www-data squabble_app_1 php artisan migrate

#
# Docker
#
up:	##@docker Start containers and display status.
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	docker-compose ps

down:	##@docker Stop and remove containers.
	docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	docker-compose ps

clean:	##@docker Uninstall and remove application services and data.
	make down
	-docker-compose rm --force -v
	-docker volume rm squabble_db

build:	##@docker Build image from Dockerfile.
	docker build -t savaslabs/squabble .

logs:	##@docker Print logs.
	docker logs -f squabble_app_1

#
# Tests
#
test:	##@test Run PHPUnit and Behat tests
	make phpunit
	make behat

phpunit:##@test Run PHPUnit tests.
	make pull-db
	docker exec squabble_app_1 phpunit --colors=always

behat:	##@test Run Behat test suite.
	make pull-db
	docker run --rm -w /var/www/html --network=host --entrypoint=vendor/bin/behat --volumes-from=squabble_app_1 squabble_app -c behat/behat.yml --colors -f progress

wip:	##@test Run Behat tests tagged @wip.
	make pull-db
	docker run --rm -w /var/www/html --network=host --entrypoint=vendor/bin/behat --volumes-from=squabble_app_1 squabble_app -c behat/behat.yml --colors -f pretty --tags=@wip --colors

test:
	make phpunit
	make behat
