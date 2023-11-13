#!/bin/bash

PHP_VERSION="7.4"

echo " > MySQL - MariaDB Install"

apt-get -qq -y install mariadb-server > /dev/null

echo " > MySQL - Configure"

mkdir -p /var/log/mysql
rm -f /etc/mysql/mariadb.conf.d/provision.cnf
cp $CONFIG_FOLDER/mysql.cnf /etc/mysql/mariadb.conf.d/provision.cnf

echo " > Restart MySQL"

systemctl restart mysql    > /dev/null
systemctl is-enabled mysql > /dev/null || systemctl enable mysql > /dev/null
systemctl status mysql     > /dev/null
