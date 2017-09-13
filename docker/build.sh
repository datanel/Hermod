#!/bin/sh

set -ex

# You should be on origin/release
VERSION=$(git describe --tags --exact-match)

if [ ! -f 'docker/config.env' ]; then
    echo "'docker/config.env' environment file is missing. Please create it, see docker/config.env.dist for example"
    exit 66 # EX_NOINPUT
fi

docker build --build-arg VERSION=${VERSION} --rm -t ${DOCKER_REGISTRY_HOST}/hermod_php:${VERSION} -f docker/php/Dockerfile .
docker build --rm -t ${DOCKER_REGISTRY_HOST}/hermod_nginx:${VERSION} -f docker/nginx/Dockerfile .
