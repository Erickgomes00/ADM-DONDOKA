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

# Ajusta permissões
RUN chown -R www-data:www-data /opt/render/project/src

# Ajusta o DocumentRoot do Apache
RUN sed -i 's|/var/www/html|/opt/render/project/src|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
