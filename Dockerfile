FROM php:8.3-fpm

# Instalar extensões básicas
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    && docker-php-ext-install pdo_mysql gd bcmath

WORKDIR /var/www/html

# Copia tudo o que tem na pasta
COPY . .

# Cria as pastas do Laravel na marra se elas não existirem
RUN mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# Dá permissão em tudo para o servidor não reclamar
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
