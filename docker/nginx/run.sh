#!/bin/sh

/srv/hermod/docker/wait-for-it.sh -t 0 php-fpm:9000 && \

nginx -g "daemon off;"
