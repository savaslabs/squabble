install:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	- cd source && composer install
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	- touch source/storage/database.sqlite
	- docker exec -it squabble_web_1 php artisan migrate

clean:
	- docker-compose rm --force
	- rm -rf source/vendor
	- rm -f source/storage/database.sqlite

up:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d

down:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml down

test:
	- rm -rf source/storage/database.sqlite
	- touch source/storage/database.sqlite
	- docker exec -it squabble_web_1 php artisan migrate
	- docker exec -it squabble_web_1 phpunit
	- vendor/bin/behat