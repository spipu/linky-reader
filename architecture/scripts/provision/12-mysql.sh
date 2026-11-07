#!/bin/bash

showMessage " > MySQL - MariaDB Install (Debian package, as on the Raspberry Pi)"

apt-get -qq -y install mariadb-server > /dev/null

showMessage " > MySQL - Configure"

mkdir -p /var/log/mysql
createFromTemplate "$CONFIG_FOLDER/mysql/mysql.cnf" "/etc/mysql/mariadb.conf.d/provision.cnf"

showMessage " > MySQL - Service"

if [[ "$ENV_TYPE" = "docker" ]]; then
    mkdir -p /var/run/mysqld
    chown mysql:root /var/run/mysqld

    createFromTemplate "$CONFIG_FOLDER/mysql/mysql.sh" "/etc/init.d/mysql"

    chown root:root /etc/init.d/mysql
    chmod 755       /etc/init.d/mysql

    update-rc.d mysql defaults
    /etc/init.d/mysql start > /dev/null
else
    systemctl restart mysql    > /dev/null
    systemctl is-enabled mysql > /dev/null || systemctl enable mysql > /dev/null
    systemctl status mysql     > /dev/null
fi
