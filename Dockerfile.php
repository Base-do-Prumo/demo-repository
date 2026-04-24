FROM php:8.3-fpm-alpine

WORKDIR /var/www/html/backend

RUN docker-php-ext-install pdo pdo_mysql

COPY backend /var/www/html/backend

EXPOSE 9000
CMD ["php-fpm"]
