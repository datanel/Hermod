FROM nginx:1.12

WORKDIR /srv/hermod

COPY docker/nginx/conf.d/vhost.conf /etc/nginx/conf.d/default.conf

COPY docker/nginx/run.sh /run.sh
COPY docker/wait-for-it.sh /wait-for-it.sh

COPY web/robots.txt /srv/hermod/web/robots.txt
COPY web/favicon.ico /srv/hermod/web/favicon.ico

CMD ["/run.sh"]