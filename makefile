mercure: 
	./mercure --jwt-key='!ChangeMe!' --addr='localhost:3000' --allow-anonymous --cors-allowed-origins='http://localhost:8000'


server:
	php -S localhost:8000 -t public