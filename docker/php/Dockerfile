FROM php:7.1-fpm

WORKDIR /srv/hermod

RUN apt-get update

# Install pdo extension for Postgre
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install opcache extension for PHP accelerator
RUN docker-php-ext-install opcache \
    && docker-php-ext-enable opcache

# Install internationalization extension
RUN apt-get install -yq \
    g++ \
    libicu-dev \
	&& docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN rm -rf /var/lib/apt/lists/* \
    && apt-get autoremove -y

RUN echo 'date.timezone = "Europe/Paris"' > /usr/local/etc/php/php.ini
RUN echo 'short_open_tag = Off' > /usr/local/etc/php/php.ini
RUN echo 'opcache.max_accelerated_files = 20000' >> /usr/local/etc/php/conf.d/docker-php-ext-pgsql.ini

VOLUME ["/srv/hermod"]

CMD ["/srv/hermod/docker/php/run.sh"]
