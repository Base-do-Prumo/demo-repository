FROM php:8.3-fpm

# Instalar dependências do sistema e extensões PHP necessárias para o ERP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql gd bcmath

WORKDIR /var/www/html

# Copiar os arquivos do projeto para dentro do container
COPY . .

# Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
