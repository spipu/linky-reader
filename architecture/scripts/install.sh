#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_TYPE="none"
ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

showTitle "Install"

cd ${ENV_FOLDER}/${WEB_FOLDER}

showMessage "Environment"
createFromTemplate "${CONFIG_FOLDER}/symfony/env.local" "./.env.local"

showMessage "Composer"
composer install

showMessage "Assets"
./bin/console assets:install --symlink --relative
./bin/console spipu:assets:install

showMessage "Doctrine Schema"
./bin/console doctrine:schema:update --force --dump-sql

showMessage "Clean Symfony Cache"
set +e
rm -rf ./var/* > /dev/null 2>&1
sudo -u www-data rm -rf ./var/* > /dev/null 2>&1
set -e

sudo -u www-data mkdir -p ./var/cache
sudo -u www-data mkdir -p ./var/log

showMessage "Clean Spipu Configuration Cache"
sudo -u www-data ./bin/console spipu:configuration:clear-cache

showMessage "Clean Spipu UI Default Grids"
sudo -u www-data ./bin/console spipu:ui:grid-config:reset

showMessage "Fixtures"
sudo -u www-data ./bin/console spipu:fixtures:load

showMessage "End"
