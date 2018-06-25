#!/bin/bash

docker-compose up -d --build
docker-compose run --rm composer install
docker-compose run --rm --entrypoint php  -w /var/www/html/app php bin/phinx migrate

cd web/app
./bin/codecept run

