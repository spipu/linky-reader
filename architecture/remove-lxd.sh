#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$CURRENT_SCRIPT")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../

ENV_TYPE="lxd"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

ENV_IP=`getent hosts ${ENV_HOST} | awk '{ print $1 }'`

cd ./$ARCHITECTURE_FOLDER/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

lxd-remove

cd - > /dev/null
echo ""
