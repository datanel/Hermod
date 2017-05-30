#!/bin/sh

# Initialize environment variables
. docker/php/init_vars.sh

setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions

./bin/console cache:clear --env dev
./bin/console cache:warmup --env dev

./docker/wait-for-it.sh -t 0 ${POSTGRES_HOST}:${POSTGRES_PORT} && \

./bin/console doctrine:migrations:migrate --env dev

# Launch php-fpm as PID 1
exec php-fpm --nodaemonize
