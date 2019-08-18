# Release-note app

## requirerements
	Docker 
	docker-compose

## back-end installation

create containers in docker with docker-compose file :
	docker/docker-compose up --build

install php back-end dependencies using composer container :
	docker-compose run --rm php-composer install
	docker-compose run --rm php-composer dumpautoload -o

see logs in php container:
	docker logs -f --details php

logs in slim framework app in 
	release-note/back-end/logs/app.log.

for testing email, put gmail account and password for stmp
	release-note/back-end/src/settings.php

Login in application :
	login 		: admin
	password 	: test1234

## front-end build (for prod)

when front finished, build front with npm
	npm run build

and put the folder in :
	release-note/front-end
to test front in production.



