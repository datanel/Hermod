#!/bin/sh

if [ ! -f 'docker/development.env' ]; then
    echo "'docker/development.env' environment file is missing. Please create it, see docker/default.env for example"
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
    --env-file ./docker/defaults.env \
    --env-file ./docker/development.env \
    --env SYMFONY_ENV=dev \
    --label "traefik.enable=false" \
    composer:latest \
    composer install --ignore-platform-reqs --no-interaction --prefer-dist

rm -rf var/cache/* var/logs/* var/sessions/*

docker build --rm -t hermod_php:dev -f docker/php/Dockerfile.dev .
