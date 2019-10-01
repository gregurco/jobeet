up:
	docker-compose up -d

stop:
	docker-compose stop

php:
	docker-compose exec php-fpm sh

mysql:
	docker-compose exec mysql sh

mailhog:
	docker-compose exec mailhog sh

rebuild:
	docker-compose up -d --build --force-recreate

logs:
	docker-compose logs -f
