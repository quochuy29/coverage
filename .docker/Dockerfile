# docker image base
ARG BASE_NGINX=nginx:1.21.5-alpine
ARG BASE_PHP=php:8.1.0-fpm-alpine

FROM $BASE_NGINX as nginx-builder
COPY .docker/nginx/templates/* /etc/nginx/templates/
## php-fpm-alpine-builder image: install common php extension need for project
FROM $BASE_PHP AS php-fpm-alpine-builder
LABEL maintainer="huypq <phanquochuyqthm@gmail.com>"
ENV TZ=Asia/Tokyo
ENV PYTHONUNBUFFERED=1
RUN set -eux; \
        \
        apk update; \
        \
        echo "CHANGE TIME ZONE TO '$TZ' AT BUILD TIME ONLY"; \
            apk add --no-cache tzdata; \
            cp /usr/share/zoneinfo/$TZ /etc/localtime; \
            echo $TZ > /etc/timezone; \
            echo "Test current date: $(date)"; \
            echo "timezone will reset to UTC if we run command 'apk del tzdata' so no run command until this bug fixed by alpine maintainer"; \
        \
        echo "INSTALL  PHP EXTENSION NEEDED 'pdo_mysql' 'opcache'"; \
            apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
            docker-php-ext-install -j "$(nproc)" pdo_mysql; \
        \
        echo "INSTALL PHP EXTENSION 'blackfire.so'"; \
            version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
            && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
            && mkdir -p /tmp/blackfire \
            && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
            && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
            && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8307\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
            && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz; \
        \
         echo "INSTALL xlsx2csv"; \
            apk add --no-cache python3 py3-pip; \
            pip install xlsx2csv; \
        echo 'CLEAN UP TEMP TO REDUCE DOCKER IMAGE SIZES'; \
        rm -rf /tmp/* /var/cache/apk/*

## php-fpm-prod image: install php extension support for production env
FROM php-fpm-alpine-builder AS php-fpm-prod

# tag help inspect docker image in the future
ARG GIT_COMMIT_HASH=unspecified
ARG GIT_BRANCH_NAME=unspecified

LABEL maintainer="huypq <phanquochuyqthm@gmail.com>" \
    git.commit=$GIT_COMMIT_HASH \
    git.branch=$GIT_BRANCH_NAME
RUN set -eux; \
        \
        docker-php-ext-enable opcache; \
        cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
        \
        echo 'CLEAN UP TEMP TO REDUCE DOCKER IMAGE SIZES'; \
        rm -rf /tmp/* /var/cache/apk/*
COPY .docker/php/conf.d/z-opcache.prod.ini .docker/php/conf.d/z-php.prod.ini $PHP_INI_DIR/conf.d/

#####################################################################################################################
## coverage-public: bundle  public source code (js,css). use this image on read-only env (production, staging) ##
#####################################################################################################################
FROM nginx-builder as coverage-public

# tag help inspect docker image in the future
ARG GIT_COMMIT_HASH=unspecified
ARG GIT_BRANCH_NAME=unspecified

LABEL maintainer="huypq <phanquochuyqthm@gmail.com>" \
    git.commit=$GIT_COMMIT_HASH \
    git.branch=$GIT_BRANCH_NAME

WORKDIR /var/www/html/coverage/public
COPY .docker/nginx/conf.d/coverage-app.conf /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=nginx:nginx public .
EXPOSE 80

##########################################################################################################
## coverage-vendor ##
##########################################################################################################
FROM composer:2.3.5 AS vendor
WORKDIR /var/www/html/coverage
COPY --chown=www-data:www-data composer.json artisan ./
RUN set -eux; \
composer install \
        --quiet \
        --ignore-platform-reqs \
        --no-interaction \
        --no-progress \
        --no-suggest \
        --no-scripts \
        --prefer-dist \
        --no-autoloader \
        --no-dev;
##########################################################################################################
## coverage-app: bundle  web app source code. use this image on read-only env (production, staging) ##
##########################################################################################################
FROM php-fpm-prod AS coverage-app

# tag help inspect docker image in the future
ARG GIT_COMMIT_HASH=unspecified
ARG GIT_BRANCH_NAME=unspecified

LABEL maintainer="huypq <phanquochuyqthm@gmail.com>" \
    git.commit=$GIT_COMMIT_HASH \
    git.branch=$GIT_BRANCH_NAME

ENV APPLICATION_ENV=production

WORKDIR /var/www/html/coverage
COPY --chown=www-data:www-data app app
COPY --chown=www-data:www-data bootstrap bootstrap
COPY --chown=www-data:www-data config config
COPY --chown=www-data:www-data database database
COPY --chown=www-data:www-data lang lang
COPY --chown=www-data:www-data storage storage
COPY --chown=www-data:www-data resources resources
COPY --chown=www-data:www-data routes routes
COPY --chown=www-data:www-data tools tools

COPY --chown=www-data:www-data composer.json artisan
COPY --chown=www-data:www-data public/*.php public/mix-manifest.json public/
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
COPY --from=vendor --chown=www-data:www-data /var/www/html/coverage/vendor /var/www/html/coverage/vendor
VOLUME ["/var/www/html/coverage/storage/app/public"]
RUN set -eux; \
        echo "$GIT_BRANCH_NAME - $GIT_COMMIT_HASH" > VERSION; \
        echo "DUMP autoload class for production"; \
        cd /var/www/html/coverage && \
        composer dumpautoload -a;
RUN ["chmod", "+x", "/var/www/html/coverage/tools/runInRemote.sh"]

CMD ["php-fpm"]
EXPOSE 9000

## php-fpm-dev image: install php extension support for developer
FROM php-fpm-alpine-builder AS php-fpm-dev
ARG USER_ID=1000
ARG GROUP_ID=1000
ENV COMPOSER_CACHE_DIR=/tmp
WORKDIR /var/www/html/coverage
RUN set -eux; \
        echo "INSTALL NPM nodejs, Xdebug"; \
            apk add nodejs-current;\
            apk add --update npm;\
            apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
            pecl install xdebug-3.1.2; \
            docker-php-ext-enable xdebug; \
        \
        apk del .build-deps; \
        cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
        \
        echo 'CLEAN UP TEMP TO REDUCE DOCKER IMAGE SIZES'; \
        rm -rf /tmp/* /var/cache/apk/*
COPY .docker/php/conf.d/z-xdebug.dev.ini .docker/php/conf.d/z-php.dev.ini $PHP_INI_DIR/conf.d/
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
