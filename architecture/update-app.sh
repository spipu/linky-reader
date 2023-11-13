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

set +e
rm -rf "$FOLDER/website/var/cache" > /dev/null 2>&1
rm -rf "$FOLDER/website/var/log"   > /dev/null 2>&1
sudo -u www-data rm -rf "$FOLDER/website/var/cache" > /dev/null 2>&1
sudo -u www-data rm -rf "$FOLDER/website/var/log"   > /dev/null 2>&1
set -e

echo ""
echo "==[COMPOSER UPDATE]=="
echo ""

cd "$FOLDER/website"
composer install

echo ""
echo "==[ASSETS]=="
echo ""

./bin/console assets:install --symlink --relative
./bin/console spipu:assets:install

echo ""
echo "==[DOCTRINE]=="
echo ""

./bin/console doctrine:schema:update --force --dump-sql --complete

echo ""
echo "==[CLEAN FILES]=="
echo ""

set +e
rm -rf "$FOLDER/website/var/cache" > /dev/null 2>&1
rm -rf "$FOLDER/website/var/log"   > /dev/null 2>&1
sudo -u www-data rm -rf "$FOLDER/website/var/cache" > /dev/null 2>&1
sudo -u www-data rm -rf "$FOLDER/website/var/log"   > /dev/null 2>&1
set -e

echo ""
echo "==[FIXTURES]=="
echo ""

sudo -u www-data ./bin/console spipu:fixtures:load

echo ""
echo "==[FINISHED]=="
echo ""
