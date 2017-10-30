#!/bin/sh

set -ex

# You should be on origin/release
VERSION=$(git describe --abbrev=0 --tags)

if [ ! -f 'docker/config.env' ]; then
    echo "'docker/config.env' environment file is missing. Please create it, see docker/config.env.dist for example"
    exit 66 # EX_NOINPUT
fi

docker build --build-arg VERSION=${VERSION} --rm -t kisiodigital/hermod_php:${VERSION} -f docker/php/Dockerfile .
docker build --rm -t kisiodigital/hermod_nginx:${VERSION} -f docker/nginx/Dockerfile .
