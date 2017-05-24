#!/bin/sh

. ./docker/defaults.env
. ./docker/production.env

# Export env vars for bin/console and php-fpm to use them in SF3 conf
export POSTGRES_HOST POSTGRES_PORT POSTGRES_USER POSTGRES_PASSWORD POSTGRES_DB

./bin/console cache:clear --env prod

# Launch php-fpm as PID 1
exec php-fpm --nodaemonize
