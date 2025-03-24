# Utilisation d'une image PHP officielle avec Apache
FROM php:8.1-apache

# Installation des extensions nécessaires
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libicu-dev zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl \
    && apt-get clean

# Activation du module Apache mod_rewrite
RUN a2enmod rewrite

# Installation de Composer (pour gérer les dépendances)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copie de votre code source dans le container
COPY . /var/www/html/

# Installation des dépendances PHP via Composer (inclut phpdotenv et phpmailer)
WORKDIR /var/www/html
RUN composer require vlucas/phpdotenv phpmailer/phpmailer

# Exposer le port 80
EXPOSE 80
