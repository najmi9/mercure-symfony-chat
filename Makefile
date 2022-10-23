.PHONY: mercure install-mercure

CURRENT_DIR=$(shell pwd)

MSG="initial commit"
BRANCH="master"

user= $(id -u)
group= $(id -g)

DIR=$(CURRENT_DIR)/mercure_binary

install-mercure: ##  make install-mercure DIR="/path/when/mercure/willbe/installed"
	rm -rf $(DIR)
	mkdir $(DIR)
	wget https://github.com/dunglas/mercure/releases/download/v0.10.4/mercure_0.10.4_Linux_x86_64.tar.gz -P $(DIR)
	tar -xzf $(DIR)/mercure_0.10.4_Linux_x86_64.tar.gz

mercure: 
	$(DIR)/mercure --jwt-key='cocolesamis' --publisher-jwt-key='najmi' --subscriber-jwt-key='imad' --addr='localhost:3000' --publish-allowed-origins='http://localhost:3000' --allow-anonymous=0 --cors-allowed-origins="http://localhost:8000"


server:
	php -S localhost:8000 -t public

php-stan:
	vendor/bin/phpstan analyse -c phpstan.neon.dist

cs-fixer:
	./php-cs-fixer --diff --dry-run -v --allow-risky=yes fix

worker:
	php bin/console messenger:consume async -vvv

git:
	git add .
	git commit -m "$(MSG)"
	git push origin $(BRANCH)

.PHONY: docker-up
docker-up:
	USER_ID=1000 GROUP_ID=1000 docker compose up --build