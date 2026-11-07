#!/bin/bash

if [[ ! "${ENV_TYPE}" ]] || [[ ! "${ARCHITECTURE_FOLDER}" ]] || [[ ! "${WEB_FOLDER}" ]]; then
  echo "ERROR - You must not call this script directly"
  exit 1
fi

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[CLEAN]==="
ssh root@${ENV_HOST} -p ${ENV_SSH_PORT} "rm -rf ${ENV_FOLDER}/${WEB_FOLDER}/var"
ssh root@${ENV_HOST} -p ${ENV_SSH_PORT} "rm -rf ${ENV_FOLDER}/${WEB_FOLDER}/vendor"
(cd ./${WEB_FOLDER} && composer install --ignore-platform-reqs --no-scripts)
echo ""

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[PREPARE ALL]==="

ssh root@${ENV_HOST} -p ${ENV_SSH_PORT} $ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/prepare-all.sh "$ENV_TYPE"

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[FINISHED]==="
