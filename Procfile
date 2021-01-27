web: heroku-php-apache2 public/
worker: ADDR=:$PORT ./mercure --jwt-key='cocolaesamis' --debug --allow-anonymous --cors-allowed-origins='*' --publish-allowed-origins='*'
