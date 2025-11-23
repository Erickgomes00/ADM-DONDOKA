FROM php:8.2-apache

# Instala dependências do PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ativa módulos necessários
RUN a2enmod rewrite
RUN a2enmod auth_basic

# Copia o projeto para o caminho correto do Render
COPY . /opt/render/project/src

# Gera o .htpasswd
RUN htpasswd -bc /opt/render/project/src/.htpasswd adm 123456

# Ajusta DocumentRoot para o caminho do Render
RUN sed -i 's|/var/www/html|/opt/render/project/src|g' /etc/apache2/sites-available/000-default.conf

# Remove o <Directory /var/www/html> e cria um novo
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/d' /etc/apache2/apache2.conf

RUN echo '<Directory /opt/render/project/src>' \
    '\n    Options Indexes FollowSymLinks' \
    '\n    AllowOverride All' \
    '\n    Require all granted' \
    '\n</Directory>' \
    >> /etc/apache2/apache2.conf

# Permissões
RUN chown -R www-data:www-data /opt/render/project/src

EXPOSE 80

CMD ["apache2-foreground"]
