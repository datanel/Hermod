#!/bin/sh

# Initialize environment variables
. docker/php/init_vars.sh

setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions

./bin/console cache:clear --env dev
./bin/console cache:warmup --env dev

./docker/wait-for-it.sh -t 0 ${POSTGRES_HOST}:${POSTGRES_PORT} && \

./bin/console doctrine:migrations:migrate --env dev --no-interaction

php-fpm --daemonize

./bin/console server:start 127.0.0.1:8080

./vendor/bin/phpunit --bootstrap vendor/autoload.php -c phpunit.xml

./bin/console server:stop