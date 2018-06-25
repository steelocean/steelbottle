SteelBottle
===========

Quickstart
==========

$ git clone https://github.com/steelocean/steelbottle.git
$ cd steelbottle
$ docker-compose up -d --build
$ docker-compose run --rm composer install
$ cd web/app
$ ./bin/codecept run


TODO
====
* Add validation so that 400 or 409 can be returned
* Add duplicate task checking
* Add duplicate todolist checking

