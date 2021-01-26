.PHONY: mercure install-mercure

CURRENT_DIR=$(shell pwd)

DIR=$(CURRENT_DIR)/mercure_binary

install-mercure: ##  make install-mercure DIR="/path/when/mercure/willbe/installed"
	rm -rf $(DIR)
	mkdir $(DIR)
	wget https://github.com/dunglas/mercure/releases/download/v0.10.4/mercure_0.10.4_Linux_x86_64.tar.gz -P $(DIR)
	tar -xvzf $(DIR)/mercure_0.10.4_Linux_x86_64.tar.gz
	ll $(DIR)



mercure: 
	$(DIR)/mercure --jwt-key='cocolesamis' --addr='https://najmi-chat.herokuapp.com:5000' --debug=1 --allow-anonymous --cors-allowed-origins="https://najmi-chat.herokuapp.com"


server:
	php -S localhost:8000 -t public

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon.dist

csfixer:
	php-cs-fixer --diff --dry-run -v --allow-risky=yes fix


git:
	git add .
	git commit -m "add commit"
	git push