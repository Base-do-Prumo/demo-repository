FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
