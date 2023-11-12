#!/bin/bash

FOLDER="/var/www/linky-reader"

cd "$FOLDER"
git pull

sudo -u www-data rm -rf "$FOLDER/website/war/cache"
sudo -u www-data rm -rf "$FOLDER/website/var/log"
rm -rf "$FOLDER/website/var/cache"
rm -rf "$FOLDER/website/var/log"

cd "$FOLDER/website"
composer install

rm -rf "$FOLDER/website/var/cache"
rm -rf "$FOLDER/website/var/log"
sudo -u www-data rm -rf "$FOLDER/website/var/cache"
sudo -u www-data rm -rf "$FOLDER/website/var/log"
