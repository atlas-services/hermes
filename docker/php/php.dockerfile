FROM php:8.2-fpm-alpine

ENV PHPUSER=symfony
ENV PHPGROUP=symfony

ENV UID=1000
ENV GID=1000


RUN addgroup -g 1006 --system symfony
RUN adduser -G www-data --system -D -s /bin/sh -u 1006 symfony

# RUN ls /usr/local/etc/php-fpm.d/

# RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
# RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/public
# Add packages for symfony
RUN docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm"]
