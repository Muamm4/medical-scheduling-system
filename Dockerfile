# Use uma imagem base PHP com FPM (FastCGI Process Manager)
FROM php:8.4-fpm-alpine

# Instale as dependências do sistema
RUN apk add --no-cache \
    zip \
    unzip \
    git \
    curl \
    mysql-client \
    mysql-dev \
    libpng-dev \
    jpeg-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    npm

# Instale as extensões PHP necessárias para Laravel
RUN docker-php-ext-install pdo_mysql bcmath exif pcntl gd intl opcache

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie a aplicação Laravel
COPY . .

# Instale as dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Permissões de arquivo para o Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Exponha a porta onde o servidor PHP embutido irá rodar
EXPOSE 8000

# Comando para iniciar o servidor PHP embutido do Laravel
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]