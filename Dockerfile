# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Atualiza pacotes e instala extensões necessárias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pgsql pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ativa mod_rewrite do Apache (muito útil para PHP)
RUN a2enmod rewrite

# Copia todo o projeto para o diretório do Apache
COPY . /var/www/html

# Ajusta permissões para uploads e logs
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Define o diretório padrão do Apache
WORKDIR /var/www/html

# Expondo a porta 80
EXPOSE 80

# Comando padrão para iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
