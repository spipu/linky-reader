#!/bin/bash

PHP_VERSION="7.3"

echo " > Install Apache + PHP"

apt-get -qq -y install \
  apache2 \
  libapache2-mod-php${PHP_VERSION} \
  php${PHP_VERSION}-cli \
  php${PHP_VERSION}-bcmath \
  php${PHP_VERSION}-curl \
  php${PHP_VERSION}-gd \
  php${PHP_VERSION}-iconv \
  php${PHP_VERSION}-intl \
  php${PHP_VERSION}-json \
  php${PHP_VERSION}-mbstring \
  php${PHP_VERSION}-mysql \
  php${PHP_VERSION}-pdo \
  php${PHP_VERSION}-pdo-mysql \
  php${PHP_VERSION}-readline \
  php${PHP_VERSION}-simplexml \
  php${PHP_VERSION}-soap \
  php${PHP_VERSION}-xml \
  php${PHP_VERSION}-xsl \
  php${PHP_VERSION}-zip \
  > /dev/null

echo " > Configure Apache + PHP"
a2enmod rewrite > /dev/null

rm -f /etc/php/${PHP_VERSION}/cli/conf.d/99-provision.ini
ln -s $CONFIG_FOLDER/php.ini /etc/php/${PHP_VERSION}/cli/conf.d/99-provision.ini

rm -f /etc/php/${PHP_VERSION}/apache2/conf.d/99-provision.ini
ln -s $CONFIG_FOLDER/php.ini /etc/php/${PHP_VERSION}/apache2/conf.d/99-provision.ini

rm -rf /var/www/html
ln -s ${ENV_FOLDER}/website/public /var/www/html

echo " > Restart Apache"

systemctl restart apache2 > /dev/null
