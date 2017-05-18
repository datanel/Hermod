#!/bin/sh

/srv/hermod/docker/wait-for-it.sh -t 0 php-fpm:9000 && \
/srv/hermod/docker/wait-for-it.sh -t 0 database:5432 && \

nginx -g "daemon off;"
