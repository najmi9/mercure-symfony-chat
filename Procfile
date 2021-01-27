web: heroku-php-apache2 public/
worker: ADDR=:5000 ./mercure --jwt-key='cocolaesamis' --debug --allow-anonymous --cors-allowed-origins='*' --publish-allowed-origins='*'
