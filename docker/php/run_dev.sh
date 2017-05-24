#!/bin/sh

set -e

. ./docker/defaults.env
. ./docker/development.env

# Export env vars for bin/console and php-fpm to use them in SF3 conf
export POSTGRES_HOST POSTGRES_PORT POSTGRES_USER POSTGRES_PASSWORD POSTGRES_DB

setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/logs var/sessions

./bin/console cache:clear --env dev
./bin/console cache:warmup --env dev

./docker/wait-for-it.sh -t 0 ${POSTGRES_HOST}:${POSTGRES_PORT} && \

# Launch php-fpm as PID 1
exec php-fpm --nodaemonize
