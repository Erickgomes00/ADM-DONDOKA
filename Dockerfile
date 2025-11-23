# ==========================
# Dockerfile Atualizado
# ==========================

# Usa PHP 8.2 com Apache
FROM php:8.2-apache

# ==========================
# Instala dependências
# ==========================
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ==========================
# Ativa módulos do Apache
# ==========================
RUN a2enmod rewrite
RUN a2enmod auth_basic

# ==========================
# Instala Composer (para Cloudinary)
# ==========================
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ==========================
# Copia projeto para o caminho do Render
# ==========================
COPY . /opt/render/project/src

WORKDIR /opt/render/project/src

# ==========================
# Instala dependências PHP do projeto
# ==========================
RUN composer install --no-dev --optimize-autoloader

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
# Permissões corretas
# ==========================
RUN chown -R www-data:www-data /opt/render/project/src \
    && chmod -R 755 /opt/render/project/src

# ==========================
# Porta e comando final
# ==========================
EXPOSE 80
CMD ["apache2-foreground"]
