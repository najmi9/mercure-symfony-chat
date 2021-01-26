install-mercure: ##  make install-mercure DIR="/path/when/mercure/willbe/installed"
	rm -rf mercure_binary
	mkdir mercure_binary
	wget https://github.com/dunglas/mercure/releases/download/v0.10.4/mercure_0.10.4_Linux_x86_64.tar.gz -P mercure_binary
	cd mercure_binary && tar -xvzf mercure_0.10.4_Linux_x86_64.tar.gz


mercure: 
	./mercure_binary/mercure --jwt-key='cocolesamis' --addr='localhost:3000' --debug=1 --allow-anonymous --cors-allowed-origins="http://localhost:8000"


server:
	php -S localhost:8000 -t public

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon.dist

csfixer:
	php-cs-fixer --diff --dry-run -v --allow-risky=yes fix
