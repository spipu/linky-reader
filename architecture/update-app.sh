#!/bin/bash

FOLDER="/var/www/linky-reader"

echo ""
echo "==[UPDATE FROM GIT]=="
echo ""

cd "$FOLDER"
git pull

echo ""
echo "==[CLEAN FILES]=="
echo ""

sudo -u www-data rm -rf "$FOLDER/website/war/cache"
sudo -u www-data rm -rf "$FOLDER/website/var/log"
rm -rf "$FOLDER/website/var/cache"
rm -rf "$FOLDER/website/var/log"

echo ""
echo "==[COMPOSER UPDATE]=="
echo ""

cd "$FOLDER/website"
composer install

echo ""
echo "==[CLEAN FILES]=="
echo ""

rm -rf "$FOLDER/website/var/cache"
rm -rf "$FOLDER/website/var/log"
sudo -u www-data rm -rf "$FOLDER/website/var/cache"
sudo -u www-data rm -rf "$FOLDER/website/var/log"

echo ""
echo "==[FINISHED]=="
echo ""
