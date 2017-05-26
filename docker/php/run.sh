#!/bin/sh

# Initialize environment variables
. docker/php/init_vars.sh

./bin/console cache:clear --env prod
./bin/console cache:warmup --env prod

./docker/wait-for-it.sh -t 0 ${POSTGRES_HOST}:${POSTGRES_PORT} && \

# Launch php-fpm as PID 1
exec php-fpm --nodaemonize
