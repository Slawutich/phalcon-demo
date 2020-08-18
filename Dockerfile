FROM php:7.2.29-fpm-buster

ENV DOCKERIZE_VERSION v0.6.1
ENV PHALCON_VERSION=4.0.6
ENV PHALCON_DEVTOOLS_VERSION=4.0.3

RUN apt-get update \
    && apt-get install -y --no-install-recommends gnupg openssl ca-certificates nano iputils-ping procps net-tools telnet ngrep \
    \
    && apt-get install -y --no-install-recommends supervisor rsyslog cron logrotate \
    \
    && php -r "copy('http://nginx.org/keys/nginx_signing.key', 'nginx_signing.key');" \
    && apt-key add nginx_signing.key \
    && rm nginx_signing.key \
    && echo "deb http://nginx.org/packages/debian/ stretch nginx" > /etc/apt/sources.list.d/nginx.list \
    && apt-get update \
    \
    && apt-get install -y --no-install-recommends nginx \
    \
    && apt-get install -y --no-install-recommends msmtp \
    && mkdir -p /var/msmtpq/data \
    && chmod 0777 /var/msmtpq/data \
    \
    && apt-get install -y --no-install-recommends libmemcached-dev zlib1g-dev libxml2-dev \
    && pecl channel-update pecl.php.net \
    && pecl install memcached-3.1.5 \
    && pecl install psr \
    && docker-php-ext-enable memcached \
    && docker-php-ext-enable psr \
    && docker-php-ext-install pdo pdo_mysql opcache bcmath \
    \
    && pecl clear-cache \
    && apt-get purge -y --auto-remove gnupg libmemcached-dev zlib1g-dev libxml2-dev \
    && apt-get -y autoremove \
    \
    && apt-get install -y --no-install-recommends libmemcachedutil2 zlib1g libxml2 \
    \
    \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    \
    && rm /etc/cron.daily/apt-compat /etc/cron.daily/dpkg /etc/cron.daily/passwd \
    && rm /etc/logrotate.d/*


# dockerize
RUN curl -L https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz -o dockerize.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize.tar.gz \
    && rm dockerize.tar.gz


# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/local/bin/composer \
    && php -r "unlink('composer-setup.php');"

# phalcon
RUN curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz \
    && tar xzf v${PHALCON_VERSION}.tar.gz \
    && docker-php-ext-install ${PWD}/cphalcon-${PHALCON_VERSION}/build/php7/64bits \
    && rm -rf v${PHALCON_VERSION}.tar.gz cphalcon-${PHALCON_VERSION} \
    && docker-php-ext-enable phalcon

# devtools
RUN curl -LO https://github.com/phalcon/phalcon-devtools/archive/v${PHALCON_DEVTOOLS_VERSION}.tar.gz \
    && tar xzf v${PHALCON_DEVTOOLS_VERSION}.tar.gz \
    && mv phalcon-devtools-${PHALCON_DEVTOOLS_VERSION} /usr/local/phalcon-devtools \
    && ln -s /usr/local/phalcon-devtools/phalcon /usr/local/bin/phalcon \
    && chmod ugo+x /usr/local/bin/phalcon \
    && rm -f v${PHALCON_DEVTOOLS_VERSION}.tar.gz



COPY ./rootfs/ /
COPY ./www /var/www/phalcon-demo.com/www
COPY ./vendor /var/www/phalcon-demo.com/vendor

RUN chmod -R +x /usr/local/bin/


EXPOSE 80/tcp 443/tcp

VOLUME ["/etc/nginx/ssl/"]

WORKDIR /var/www

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

ENTRYPOINT ["/usr/local/bin/init.sh"]