install:
	if [ ! -f docker-compose.local.yml ]; then cp docker-compose.local.example.yml docker-compose.local.yml; fi
	if [[ $$(docker-compose ps -q) ]]; then docker-compose -f docker-compose.yml -f docker-compose.local.yml down; fi
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d --build --force-recreate
	make reset_db
	make reset_logs
	docker exec -u www-data squabble_app_1 php artisan migrate
	docker-compose ps

build:
	docker build -t savaslabs/squabble .

reset_logs:
	docker exec squabble_app_1 sh -c "if [ -f /var/www/html/storage/logs/lumen.log]; then rm /var/www/html/storage/logs/lumen.log; fi"
	docker exec -u www-data squabble_app_1 touch /var/www/html/storage/logs/lumen.log

logs:
	docker logs -f squabble_app_1

clean:
	make down
	-docker-compose rm --force -v
	-docker volume rm squabble_db

reset_db:
	docker exec squabble_app_1 sh -c "if [ -f /var/www/html/storage/database.sqlite ]; then rm /var/www/html/storage/database.sqlite; fi"
	docker exec -u www-data squabble_app_1 touch /var/www/html/storage/database.sqlite
	docker exec -u www-data squabble_app_1 php artisan migrate

up:
	docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	docker-compose ps

down:
	docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	docker-compose ps

phpunit:
	make reset_db
	docker exec squabble_app_1 phpunit --colors=always

behat:
	make reset_db
	docker run --rm -w /var/www/html --network=host --entrypoint=vendor/bin/behat --volumes-from=squabble_app_1 squabble_app -c behat/behat.yml --colors -f progress

wip:
	make reset_db
	docker run --rm -w /var/www/html --network=host --entrypoint=vendor/bin/behat --volumes-from=squabble_app_1 squabble_app -c behat/behat.yml --colors -f pretty --tags=@wip --colors

test:
	make phpunit
	make behat

.PHONY: clean install test down up wip behat phpunit reset_db reset_logs logs
