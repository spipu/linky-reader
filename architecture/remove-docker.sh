#!/bin/bash

set -e

if [[ -d "/opt/homebrew/opt/gnu-sed/libexec/gnubin" ]]; then
  PATH="/opt/homebrew/opt/gnu-sed/libexec/gnubin:$PATH"
  CURRENT_SCRIPT=$(greadlink -f "${BASH_SOURCE[0]}")
elif [[ -d "/opt/local/opt/gnu-sed/libexec/gnubin" ]]; then
  PATH="/opt/local/opt/gnu-sed/libexec/gnubin:$PATH"
  CURRENT_SCRIPT=$(greadlink -f "${BASH_SOURCE[0]}")
else
  CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
fi
ARCHITECTURE_FOLDER=$(basename "$(dirname "$CURRENT_SCRIPT")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../

ENV_TYPE="docker"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

cd ./$ARCHITECTURE_FOLDER/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

ssh-keygen -R "${ENV_HOST}"
ssh-keygen -R "${ENV_IP}"

sudo sed "/${ENV_HOST}/d" -i /etc/hosts

sudo docker-compose down -v

cd - > /dev/null
echo ""
