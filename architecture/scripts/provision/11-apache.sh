#!/bin/bash

showMessage " > Apache - Configure"

a2enmod expires > /dev/null
a2enmod headers > /dev/null
a2enmod rewrite > /dev/null

if ! grep "$ENV_NAME" /etc/apache2/apache2.conf > /dev/null; then
    echo "# Added by $ENV_NAME Provisioning" >> /etc/apache2/apache2.conf
    echo "ServerName $ENV_HOST"              >> /etc/apache2/apache2.conf
fi

rm -f /etc/apache2/sites-available/*
rm -f /etc/apache2/sites-enabled/*

createFromTemplate "$CONFIG_FOLDER/apache/virtualhost.conf"                "/etc/apache2/sites-available/$ENV_NAME.conf"
createFromTemplate "$CONFIG_FOLDER/apache/virtualhost-common-symfony.conf" "/etc/apache2/sites-available/$ENV_NAME-common-symfony.conf"
ln -s "/etc/apache2/sites-available/$ENV_NAME.conf" /etc/apache2/sites-enabled/

showMessage " > Apache - Prepare Document Root"
rm -rf /var/www/html

showMessage " > Apache - Service"

if [[ "$ENV_TYPE" = "docker" ]]; then
    /etc/init.d/apache2 restart > /dev/null
else
    systemctl restart apache2    > /dev/null
    systemctl is-enabled apache2 > /dev/null || systemctl enable apache2 > /dev/null
fi
