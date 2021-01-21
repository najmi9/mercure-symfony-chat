mercure: 
	./mercure_binary/mercure --jwt-key='cocolesamis' --addr='localhost:3000' --allow-anonymous --cors-allowed-origins='http://localhost:8000'


server:
	php -S localhost:8000 -t public

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon.dist

csfixer:
	php-cs-fixer --diff --dry-run -v --allow-risky=yes fix
