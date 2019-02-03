FROM php:7-alpine3.8

RUN apk --no-cache add --virtual tini bash

RUN echo "auto_prepend_file=/var/nanbando/recipes/common.php" > "$PHP_INI_DIR/conf.d/auto_prepend_file.ini"

COPY docker-entrypoint.sh /docker-entrypoint.sh
COPY . /var/nanbando

RUN ln -s /var/nanbando/bin/nanbando /usr/local/bin/nanbando

WORKDIR /app

ENTRYPOINT ["/bin/sh", "/docker-entrypoint.sh"]

CMD ["nanbando"]
