#!make
include .env
export $(shell sed 's/=.*//' .env)

clear:
	rm load.yaml monitoring.xml token.txt | true

rps100: clear
	sed -e "s~%SERVER%~$$SERVER~g;" -e "s~%RPS%~line(1, 100, 60s) const(100, 540s)~g" load.template.yaml > load.yaml
	sed -e "s~%SERVER%~$$SERVER~g" -e "s~%USERNAME%~$$USERNAME~g" monitoring.template.xml > monitoring.xml
	echo $$TOKEN > token.txt
	docker run -v $$(pwd):/var/loadtest -v $$HOME/.ssh:/root/.ssh --net host -it direvius/yandex-tank

rps1000: clear
	sed -e "s~%SERVER%~$$SERVER~g;" -e "s~%RPS%~line(1, 1000, 60s) const(1000, 60s)~g" load.template.yaml > load.yaml
	sed -e "s~%SERVER%~$$SERVER~g" -e "s~%USERNAME%~$$USERNAME~g" monitoring.template.xml > monitoring.xml
	echo $$TOKEN > token.txt
	docker run -v $$(pwd):/var/loadtest -v $$HOME/.ssh:/root/.ssh --net host -it direvius/yandex-tank

rps10000: clear
	sed -e "s~%SERVER%~$$SERVER~g;" -e "s~%RPS%~line(1, 10000, 30s) const(10000, 30s)~g" load.template.yaml > load.yaml
	sed -e "s~%SERVER%~$$SERVER~g" -e "s~%USERNAME%~$$USERNAME~g" monitoring.template.xml > monitoring.xml
	echo $$TOKEN > token.txt
	docker run -v $$(pwd):/var/loadtest -v $$HOME/.ssh:/root/.ssh --net host -it direvius/yandex-tank
