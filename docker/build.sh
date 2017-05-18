#!/bin/sh

if [ ! -f 'app/config/parameters.yml' ]; then
    echo "'app/config/parameters.yml' environment file is missing. Please create it, see app/config/parameters.yml.dist for example"
    exit 66 # EX_NOINPUT
fi

if [ ! -f 'docker/database.prod.env' ]; then
    echo "'docker/database.prod.env' environment file is missing. Please create it, see docker/database.prod.env.dist for example"
    exit 66 # EX_NOINPUT
fi

docker build --rm -t hermod:php_7.1-fpm -f docker/php/Dockerfile .

docker run --rm --interactive --tty \
    --user $(id -u $USER) \
    --volume /etc/passwd:/etc/passwd:ro \
    --volume /etc/group:/etc/group:ro \
    --volume ${HOME}/.composer/.config/composer:/composer:rw \
    --volume ${HOME}/.ssh:$HOME/.ssh:ro \
    --volume ${PWD}:/app \
    --workdir /app \
    --label "traefik.enable=false" \
    composer:latest \
    composer install --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist
