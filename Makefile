install:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml down
	- cd source && composer install
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	- touch source/storage/database.sqlite
	- touch source/storage/logs/lumen.log
	- docker exec -it squabble_web_1 php artisan migrate
	- composer install

install_travis:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
	- docker exec squabble_web_1 touch /var/www/html/storage/database.sqlite
	- docker exec squabble_web_1 cd /var/www/html && composer install
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