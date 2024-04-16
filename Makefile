.PHONY: build stop start seed logs app-login migrate keygenerate restart seed

build: stop
	docker-compose -f docker-compose.yml build

stop:
	docker-compose -f docker-compose.yml down

start: stop
	docker-compose -f docker-compose.yml up --remove-orphans -d

restart: stop start

logs: 
	docker-compose -f docker-compose.yml realtime2_web_1 storage/logs --tail=10 -f $(c)

app-login:
	docker exec -it realtime2_web_1 /bin/bash

migrate:
	docker-compose run realtime2_web_1 php artisan migrate

seed:
	docker-compose run realtime2_web_1 php artisan migrate:refresh --seed


keygenerate:
	docker-compose exec realtime2_web_1 php artisan key:generate