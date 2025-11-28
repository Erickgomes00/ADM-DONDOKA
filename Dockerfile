# ==========================
# Dockerfile Final Atualizado
# ==========================

# Usa PHP 8.2 com Apache
FROM php:8.2-apache

# ==========================
# Instala dependências do sistema
# ==========================
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ==========================
# Ativa módulos do Apache
# ==========================
RUN a2enmod rewrite
RUN a2enmod auth_basic

# ==========================
# Instala Composer globalmente
# ==========================
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ==========================
# Define diretório do projeto
# ==========================
WORKDIR /opt/render/project/src

# ==========================
# Copia o projeto
# ==========================
COPY . /opt/render/project/src

# ==========================
# Instala dependências PHP do projeto via Composer
# ==========================
RUN composer install --no-dev --optimize-autoloader || echo "Composer já instalado"

# ==========================
# Gera o .htpasswd (usuário: adm / senha: 123456)
# ==========================
RUN htpasswd -bc /opt/render/project/src/.htpasswd adm 123456

# ==========================
# Configura Apache para o Render
# ==========================
# Ajusta DocumentRoot
RUN sed -i 's|/var/www/html|/opt/render/project/src|g' /etc/apache2/sites-available/000-default.conf

# Remove diretório antigo e cria novo com permissões corretas
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/d' /etc/apache2/apache2.conf
RUN echo '<Directory /opt/render/project/src>' \
    '\n    Options Indexes FollowSymLinks' \
    '\n    AllowOverride All' \
    '\n    Require all granted' \
    '\n</Directory>' \
    >> /etc/apache2/apache2.conf

# ==========================
# Ajusta permissões
# ==========================
RUN chown -R www-data:www-data /opt/render/project/src \
    && chmod -R 755 /opt/render/project/src

# ==========================
# Expondo porta e comando final
# ==========================
EXPOSE 3030
CMD ["apache2-foreground"]
