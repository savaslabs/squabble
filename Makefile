install:
	-docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	make reset_db
	docker exec squabble_web_1 composer install -n --prefer-dist --ansi
	docker exec squabble_web_1 php artisan migrate
	docker-compose ps

logs:
	docker logs -f squabble_web_1

clean:
	make down
	-docker-compose rm --force -v
	-docker volume rm squabble_db
	-docker volume rm squabble_logs

reset_db:
	-docker exec squabble_web_1 rm /db/database.sqlite
	docker exec squabble_web_1 touch /db/database.sqlite
	docker exec squabble_web_1 chmod a+w /db/database.sqlite
	docker exec squabble_web_1 chmod a+w /db
	-docker exec squabble_web_1 php artisan migrate

up:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	- docker-compose ps

down:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	- docker-compose ps

phpunit:
	make reset_db
	docker exec squabble_web_1 phpunit --colors=always

behat:
	make reset_db
	docker exec squabble_web_1 vendor/bin/behat -c source/behat.yml --colors --strict

wip:
	docker exec squabble_web_1 vendor/bin/behat -c source/behat.yml --colors --strict --tags=@wip

test:
	make phpunit
	make behat

.PHONY: clean install test down up
