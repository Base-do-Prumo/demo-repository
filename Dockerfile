FROM php:8.3-fpm

# Instalar dependências básicas
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    && docker-php-ext-install pdo_mysql gd bcmath

WORKDIR /var/www/html

# Copia o que tiver no repositório
COPY . .

# Criar as pastas caso não existam (isso evita o erro que deu)
RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
