#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$CURRENT_SCRIPT")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../

ENV_TYPE="lxd"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

cd ./$ARCHITECTURE_FOLDER/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

lxd-remove
lxd-deploy
echo ""

cd - > /dev/null

source ./$ARCHITECTURE_FOLDER/create-abstract.sh
