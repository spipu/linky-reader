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

# Parameters SSH
if [[ -f "$HOME/.ssh/id_ed25519.pub" ]]; then
    SSH_PUB=$(cat ~/.ssh/id_ed25519.pub)
else
    SSH_PUB=$(cat ~/.ssh/id_rsa.pub)
fi

cd ./$ARCHITECTURE_FOLDER/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

ssh-keygen -R "${ENV_HOST}" > /dev/null
ssh-keygen -R "${ENV_IP}"   > /dev/null

echo " => Prepare /etc/hosts file"
sudo sed "/${ENV_HOST}/d" -i /etc/hosts
echo "# Added for docker ${ENV_HOST}"                  | sudo tee -a /etc/hosts > /dev/null
echo "${ENV_IP} ${ENV_HOST} ${ENV_HOST_SUB_HOSTS_TXT}" | sudo tee -a /etc/hosts > /dev/null

echo " => Docker"
sudo docker-compose down -v
sudo docker-compose build --build-arg ssh_pub_key="${SSH_PUB}" ${ENV_NAME}
sudo docker-compose up -d

cd - > /dev/null

sleep 3
ssh-keygen -R "${ENV_HOST}" > /dev/null 2>&1
ssh-keygen -R "${ENV_IP}"   > /dev/null 2>&1
ssh-keyscan ${ENV_HOST}     >> ~/.ssh/known_hosts 2> /dev/null
ssh-keyscan ${ENV_IP}       >> ~/.ssh/known_hosts 2> /dev/null
echo ""

source ./$ARCHITECTURE_FOLDER/create-abstract.sh
