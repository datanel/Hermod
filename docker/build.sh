#!/bin/sh

if [ ! -f 'app/config/parameters.yml' ]; then
    echo "'app/config/parameters.yml' environment file is missing. Please create it, see app/config/parameters.yml.dist for example"
    exit 66 # EX_NOINPUT
fi

if [ ! -f 'docker/postgres/development.env' ]; then
    echo "'docker/postgres/development.env' environment file is missing. Please create it, see docker/postgres/development.env.dist for example"
    exit 66 # EX_NOINPUT
fi

mkdir -p docker/postgres/data

docker run --rm --interactive --tty \
    --user $(id -u) \
    --volume /etc/passwd:/etc/passwd:ro \
    --volume /etc/group:/etc/group:ro \
    --volume ${HOME}/.composer/.config/composer:/composer:rw \
    --volume ${HOME}/.ssh:$HOME/.ssh:ro \
    --volume ${PWD}:/app \
    --workdir /app \
    --label "traefik.enable=false" \
    composer:latest \
    composer install --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist

docker build --rm -t hermod_php:master -f docker/php/Dockerfile .
