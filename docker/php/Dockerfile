FROM php:8.2-apache

# Instalar extensiones necesarias para conectar PHP con MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite de Apache por si más adelante usas URLs amigables
RUN a2enmod rewrite