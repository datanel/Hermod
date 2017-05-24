# Hermod
Crowd sourcing API for Navitia

## Docker

## For development env (with traefik)

In this example, we suppose `hermod.localhost` should be your host

### Configurations

Run only this command with `root` user
```
echo -e "127.0.0.1\thermod.localhost" >> /etc/hosts
```

### Traefik

Create network for traefik and run it (Be careful, port: 80 should be free)

```
docker network create traefik_proxy --driver bridge --attachable
```

```
docker run --rm -d --name traefik --network traefik_proxy --publish 80:80 --volume /var/run/docker.sock:/var/run/docker.sock containous/traefik:v1.3.0-rc2 --docker --docker.watch --docker.domain=localhost
```

### Build & Run Hermod api

This command will build `hermod:php_7.1-fpm` image and run `composer install`
```
./docker/build_dev.sh
```

Now you can up hermod
```
docker-compose up -d
```

To see logs remove `-d` option or use `docker logs ...` command

Go to `http://hermod.localhost/v1/status`, the api should work

When you finished
```
docker-compose down
docker stop traefik
docker network rm traefik_proxy
```

## For production env (with traefik)

In this example, we suppose `hermod.localhost` should be your host
Configuration of Docker: Swarm mode (If not you need to do this `docker swarm init` in your shell)

### Configurations

Run only this command with `root` user
```
echo -e "127.0.0.1\thermod.localhost" >> /etc/hosts
```

Create `app/config/parameters.yml` file (see [app/config/parameters.yml.dist](app/config/parameters.yml.dist) for example) for symfony environment

```
# app/config/parameters.yml
parameters:
    database_host: 127.0.0.1
    database_port: ~
    database_name: hermod_api
    database_user: hermod_user
    database_password: hermod_password
    # You should uncomment this if you want to use pdo_sqlite
    #database_path: '%kernel.project_dir%/var/data/data.sqlite'

    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~

    # A secret key that's used to generate certain security-related tokens
    secret: ThisTokenIsNotSoSecretChangeIt
```

### Traefik

Create network for treafik and run it (Be careful, port: 80 should be free)

```
docker network create traefik_proxy --driver overlay --attachable
```

```
docker run --rm -d --name traefik --network traefik_proxy --publish 80:80 --volume /var/run/docker.sock:/var/run/docker.sock containous/traefik:v1.3.0-rc2 --docker --docker.swarmmode --docker.watch --docker.domain=localhost
```

### Build & Run Hermod api

This command will build `hermod:php_7.1-fpm` image and run `composer install`
```
./docker/build.sh
```

Now you can create hermod stack
```
docker stack deploy -c docker/docker-compose.yml hermod
```

Go to `http://hermod.localhost`, the api should works

When you finished
```
docker stack rm hermod
docker stop traefik
docker network rm traefik_proxy
```
