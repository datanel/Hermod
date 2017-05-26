#!/bin/sh

# Initialize environment variables
. docker/php/init_vars.sh

./bin/console cache:clear --env prod
./bin/console cache:warmup --env prod

# Launch php-fpm as PID 1
exec php-fpm --nodaemonize
