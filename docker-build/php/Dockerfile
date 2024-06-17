FROM php:8.3-apache
RUN apt update && docker-php-ext-install mysqli pdo pdo_mysql
ENV APACHE_DOCUMENT_ROOT=/var/www/places/ 
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN apt update
RUN apt install -y git zip wget libmcrypt-dev gcc libcurl4-openssl-dev ssl-cert
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
RUN docker-php-ext-install curl
RUN docker-php-ext-enable curl
RUN a2enmod ssl
RUN a2ensite default-ssl.conf