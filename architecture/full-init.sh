#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[LXD]==="
lxd-remove
lxd-deploy
echo ""

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[CLEAN]==="
sudo rm -rf ./website/var
echo ""

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[PROVISION]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/provision.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[CreateDB]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/createDb.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[PERMISSION]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/permissions.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[INSTALL]==="
ssh ${ENV_USER}@${ENV_HOST} $ENV_FOLDER/architecture/scripts/install.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[FINISHED]==="
