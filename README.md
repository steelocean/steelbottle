SteelBottle
===========

Quickstart
==========

* Pre-requisite is to install docker and docker-compose
* Run these commands to get up and running

```
  git clone https://github.com/steelocean/steelbottle.git
  cd steelbottle
  
  docker-compose up -d --build
  docker-compose run --rm composer update
  docker-compose run --rm --entrypoint php  -w /var/www/html/app php bin/phinx migrate
```

* Run Tests

```
  cd web/app
  ./bin/codecept run
```

* View tests

```
  $EDITOR web/app/tests/ApiCest.php
```

* Run a curl command

```
  curl http://localhost:8000/steelbottle/todo/v1/lists
```

* Access phpMyAdmin
```
  http://localhost:8080/index.php
```


TODO
====
* Add validation code to handle 400 and 409
* Add duplicate task checking
* Add duplicate todolist checking


Some Helpful Docker Commands
======

* list all images

```
  docker images -a
```

* stop instances

```
  docker stop $(docker ps -a -q)
```

* remove instances

```
  docker rm $(docker ps -a -q)
```

* prune system images

```
  docker system prune -a
```

* run composer ; i.e. PROJECT_DIR="./steelbottle"
  - base running dir is in docker-compose.yml 
  - this dockerized composer image uses ./web/app/composer.json 

```
  (cd $PROJECT_DIR;docker-compose run --rm composer install)
  (cd $PROJECT_DIR;docker-compose run --rm composer update)
```
