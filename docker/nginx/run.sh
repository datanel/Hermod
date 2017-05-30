#!/bin/sh

/wait-for-it.sh -t 0 php-fpm:9000 && \

exec nginx -g "daemon off;"
