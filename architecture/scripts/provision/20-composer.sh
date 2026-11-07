#!/bin/bash

showMessage " > Composer - Install"

rm -f /usr/local/bin/composer
wget -q https://getcomposer.org/composer.phar > /dev/null
mv ./composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
composer self-update -q > /dev/null

rm -rf /home/${ENV_USER}/.composer
