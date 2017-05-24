#!/bin/sh

if [ ! -f 'docker/production.env' ]; then
    echo "'docker/production.env' environment file is missing. Please create it, see docker/default.env for example"
    exit 66 # EX_NOINPUT
fi

docker run --rm --interactive --tty \
    --user $(id -u) \
    --volume /etc/passwd:/etc/passwd:ro \
    --volume /etc/group:/etc/group:ro \
    --volume ${HOME}/.composer/.config/composer:/composer:rw \
    --volume ${HOME}/.ssh:$HOME/.ssh:ro \
    --volume ${PWD}:/app \
    --workdir /app \
    --env-file ./docker/defaults.env \
    --env-file ./docker/production.env \
    --label "traefik.enable=false" \
    --env SYMFONY_ENV=prod \
    composer:latest \
    composer install --ignore-platform-reqs --no-interaction --prefer-dist --no-dev

rm -rf var/cache/* var/logs/* var/sessions/*

docker build --rm -t hermod_php:master -f docker/php/Dockerfile .
docker build --rm -t hermod_nginx:master -f docker/nginx/Dockerfile .
