# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Atualiza pacotes e instala extensões necessárias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pgsql pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ativa o mod_rewrite (necessário para .htaccess funcionar)
RUN a2enmod rewrite

# Copia todo o projeto para o diretório do Apache
COPY . /var/www/html

# Copia o arquivo .htpasswd para /var/www/html (onde o .htaccess está apontando)
COPY .htpasswd /var/www/html/.htpasswd

# Garante que o Apache permita usar .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
