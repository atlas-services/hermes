# On customize cette image; l'image officielle du serveur apache sous ubuntu
FROM php:8.2-apache
# FROM ubuntu/apache2:latest

# Add packages for symfony
RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring
RUN docker-php-ext-enable intl mbstring


# Variables d'environnement
ENV APACHE_RUN_USER=symfony
ENV APACHE_RUN_GROUP=symfony

# Créer notre dossier de code pour le serveur
RUN mkdir -p /var/www/html/public


# On ajoute un fichier de configuration pour qu'apache nous serve les bons fichiers
COPY vhosts/000-symfony.conf /etc/apache2/sites-available/000-symfony.conf

# # Set up Apache virtual host
###COPY apache.conf/apache-config.conf /etc/apache2/sites-available/000-default.conf
# # Set up Apache ports
# COPY apache.conf/apache-ports.conf /etc/apache2/ports.conf

# On active le "Virtual Host" : notre application symfony sera accessible grâce à ces lignes
RUN a2ensite 000-symfony.conf

# On active divers modules d'apache, nécessaire pour que symfony fonctionne
RUN a2enmod rewrite actions alias proxy_fcgi setenvif

###RUN a2enconf php8.2-fpm



# Modifier le USER de APACHE; sur ubuntu c'est dans le fichier envvars
RUN cat /etc/apache2/envvars

RUN sed -i "s/www-data/${APACHE_RUN_USER}/g" /etc/apache2/envvars

RUN cat /etc/apache2/envvars

RUN groupadd ${APACHE_RUN_GROUP}

RUN useradd -g ${APACHE_RUN_GROUP} ${APACHE_RUN_USER}

RUN service apache2 restart
