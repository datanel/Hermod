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

This command will build `hermod_php:dev` image and run `composer install`
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

Create `docker/production.env` file (see [docker/default.env](docker/default.env) for example) for symfony environment

### Traefik

Create network for treafik and run it (Be careful, port: 80 should be free)

```
docker network create traefik_proxy --driver overlay --attachable
```

```
docker run --rm -d --name traefik --network traefik_proxy --publish 80:80 --volume /var/run/docker.sock:/var/run/docker.sock containous/traefik:v1.3.0-rc2 --docker --docker.swarmmode --docker.watch --docker.domain=localhost
```

### Build & Run Hermod api

This command will build `hermod_php:master` and `hermod_nginx:master` images and run `composer install`
```
./docker/build.sh
```

Now you can create hermod stack
```
docker stack deploy -c docker/docker-compose.prod.yml hermod
```

Go to `http://hermod.localhost/v1/status`, the api should works

## Authentication

You need to be authenticated to use this API. We are using a token authentication.

You can add a new user by using the following command:

```
bin/console app:create-user <username>
```

This command will give you a token, and on every request, you will need to provide this token in the `Authorization` header.

## Stop application

When you finished
```
docker stack rm hermod
docker stop traefik
docker network rm traefik_proxy
```

## License

This application is under [AGPL-3.0](LICENSE).
