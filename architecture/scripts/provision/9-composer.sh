#!/bin/bash

echo " > Install Composer"

rm -f /usr/local/bin/composer
wget -q https://getcomposer.org/composer-stable.phar
mv ./composer-stable.phar /usr/local/bin/composer
chmod 775 /usr/local/bin/composer

