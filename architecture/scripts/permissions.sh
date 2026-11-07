#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

mkdir -p ${MAIN_FOLDER}/var
chown -R www-data:www-data ${MAIN_FOLDER}/var
chmod -R 664 ${MAIN_FOLDER}/var
chmod -R +X ${MAIN_FOLDER}/var

chmod +x ${MAIN_FOLDER}/bin/*
