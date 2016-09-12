install:
	if [ ! -f docker-compose.local.yml ]; then cp docker-compose.local.example.yml docker-compose.local.yml; fi
	-docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d --build --force-recreate
	make reset_db
	make reset_logs
	docker exec -u www-data squabble_web_1 composer install -n --prefer-dist --ansi
	docker exec squabble_web_1 php artisan migrate
	docker-compose ps

build:
	docker build -t savaslabs/squabble .

reset_logs:
	-docker exec squabble_web_1 rm /var/www/html/storage/logs/lumen.log
	docker exec squabble_web_1 touch /var/www/html/storage/logs/lumen.log
	docker exec squabble_web_1 chmod a+w /var/www/html/storage/logs
	docker exec squabble_web_1 chmod a+w /var/www/html/storage/logs/lumen.log

logs:
	docker logs -f squabble_web_1

clean:
	make down
	-docker-compose rm --force -v
	-docker volume rm squabble_db

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
	docker-compose -f behat/docker-compose.yml run --rm behat

wip:
	make reset_db
	docker-compose -f behat/docker-compose.yml run --rm behat --tags=@wip


test:
	make phpunit
	make behat

.PHONY: clean install test down up wip behat phpunit reset_db reset_logs logs
