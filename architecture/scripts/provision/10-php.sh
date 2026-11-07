#!/bin/bash

PHP_VERSION="8.3"
PHP_FOLDER="php/8.3"

showMessage " > PHP - Add repository packages.sury.org"

curl -sSL https://packages.sury.org/php/README.txt | bash - > /dev/null
apt-get -qq update > /dev/null

showMessage " > PHP - Install ${PHP_VERSION} (mod_php, as on the Raspberry Pi)"

apt-get -qq -y install \
    apache2 \
    libapache2-mod-php${PHP_VERSION} \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-iconv \
    php${PHP_VERSION}-intl \
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

showMessage " > PHP - Configure"

mkdir -p /etc/${PHP_FOLDER}/cli/conf.d/
mkdir -p /etc/${PHP_FOLDER}/apache2/conf.d/

createFromTemplate "$CONFIG_FOLDER/php/cli.ini"    "/etc/${PHP_FOLDER}/cli/conf.d/99-provision.ini"
createFromTemplate "$CONFIG_FOLDER/php/apache.ini" "/etc/${PHP_FOLDER}/apache2/conf.d/99-provision.ini"
