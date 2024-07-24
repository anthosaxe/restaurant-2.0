FROM php:8.2-apache

# Mettre à jour le système
RUN apt-get update && apt-get upgrade -y

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

#Copy application source code
COPY ./source /var/www/html


# Exposer le port 80 pour le serveur web
EXPOSE 80
