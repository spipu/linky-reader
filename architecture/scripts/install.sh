#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

source ./architecture/conf/env.sh

cd "${ENV_FOLDER}/website/"

rm -f ./.env.local
cp ${ENV_FOLDER}/architecture/conf/dev/.env.local ./.env.local

rm -rf ./var/* > /dev/null 2>&1
sudo -u www-data rm -rf ./var/* > /dev/null 2>&1

composer install

rm -rf ./var/* > /dev/null 2>&1
sudo -u www-data rm -rf ./var/* > /dev/null 2>&1
