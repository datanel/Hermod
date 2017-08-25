FROM nginx:1.12

# Install curl for healthcheck
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -yq \
    curl \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get autoremove -y

WORKDIR /srv/hermod

COPY docker/nginx/conf.d/vhost.conf /etc/nginx/conf.d/default.conf

COPY docker/nginx/run.sh /run.sh
COPY docker/wait-for-it.sh /wait-for-it.sh

COPY web/robots.txt /srv/hermod/web/robots.txt
COPY web/favicon.ico /srv/hermod/web/favicon.ico

CMD ["/run.sh"]

HEALTHCHECK --interval=30s --timeout=3s \
  CMD curl -s http://127.0.0.1/robots.txt | grep -c www.robotstxt.org || exit 1
