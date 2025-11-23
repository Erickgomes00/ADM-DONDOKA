FROM php:8.2-apache

# Ativa módulos necessários
RUN a2enmod rewrite
RUN a2enmod auth_basic

# Copia o projeto para o caminho correto do Render
COPY . /opt/render/project/src

# Remove qualquer .htpasswd antigo
RUN rm -f /opt/render/project/src/.htpasswd

# Cria o .htpasswd com usuário adm e senha 123456
RUN htpasswd -bc /opt/render/project/src/.htpasswd adm 123456

# Ajusta o DocumentRoot no Apache
RUN sed -i 's|/var/www/html|/opt/render/project/src|g' /etc/apache2/sites-available/000-default.conf

# Corrige permissões de acesso
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/d' /etc/apache2/apache2.conf

RUN echo '<Directory /opt/render/project/src>' \
    '\n    Options Indexes FollowSymLinks' \
    '\n    AllowOverride All' \
    '\n    Require all granted' \
    '\n</Directory>' \
    >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /opt/render/project/src

EXPOSE 80

CMD ["apache2-foreground"]
