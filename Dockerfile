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

# Copia o arquivo .htpasswd para a pasta do Apache
COPY .htpasswd /etc/apache2/.htpasswd

# Garante que o Apache permita .htaccess (AllowOverride All)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Ajusta permissões para uploads e logs
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Define o diretório padrão do Apache
WORKDIR /var/www/html

# Expondo a porta 80
EXPOSE 80

# Comando padrão para iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
