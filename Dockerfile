FROM php:8.3-apache

WORKDIR /var/www/html

# Copiar proyecto
COPY . /var/www/html/

# Extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar rewrite (IMPORTANTE para MVC)
RUN a2enmod rewrite

# Cambiar DocumentRoot correctamente
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permitir .htaccess
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/public.conf \
    && a2enconf public

EXPOSE 80

CMD ["apache2-foreground"]
