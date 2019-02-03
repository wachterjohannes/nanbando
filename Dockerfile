FROM composer as composer

WORKDIR /var/nanbando

COPY composer.* /var/nanbando

RUN composer global require hirak/prestissimo --no-plugins --no-scripts
RUN composer install --apcu-autoloader -o --no-dev --no-scripts --ignore-platform-reqs

FROM php:7-alpine3.8

RUN apk --no-cache add --virtual tini bash

RUN echo "auto_prepend_file=/var/nanbando/recipes/common.php" > "$PHP_INI_DIR/conf.d/auto_prepend_file.ini"

COPY docker-entrypoint.sh /docker-entrypoint.sh
COPY . /var/nanbando
COPY --from=composer /var/nanbando/vendor/ /var/nanbando/vendor/

RUN ln -s /var/nanbando/bin/nanbando /usr/local/bin/nanbando

WORKDIR /app

ENTRYPOINT ["/bin/sh", "/docker-entrypoint.sh"]

CMD ["nanbando"]
