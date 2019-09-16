install:
	composer install --no-dev --no-interaction --optimize-autoloader

clear:
	docker rm -f seeds | true

mysql: clear
	docker run --name seeds -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=app -p 3306:3306 -d mysql:latest --default-authentication-plugin=mysql_native_password

wait: mysql
	sleep 30

seeds: mysql wait
	DATABASE=mysql://root:root@$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' seeds)/app tools/bin/seed.php 10000
