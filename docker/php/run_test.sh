#!/bin/sh

# Initialize environment variables
. docker/php/init_vars.sh

./docker/wait-for-it.sh -t 0 ${POSTGRES_HOST}:${POSTGRES_PORT} && \

./bin/console doctrine:migrations:migrate --env dev --no-interaction

./bin/console server:start 127.0.0.1:8080

./vendor/bin/phpunit --bootstrap vendor/autoload.php --log-junit junit.xml -c phpunit.xml

./bin/console server:stop
