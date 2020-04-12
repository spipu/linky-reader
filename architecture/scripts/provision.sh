#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

source ./architecture/conf/env.sh

CONFIG_FOLDER="$ENV_FOLDER/architecture/conf/dev"

ENV_FOLDER_SED=$(echo "$ENV_FOLDER" | sed -e 's/[\/&]/\\&/g')

export DEBIAN_FRONTEND=noninteractive

source ./architecture/scripts/provision/0-upgrade.sh
source ./architecture/scripts/provision/1-packages.sh
source ./architecture/scripts/provision/2-apache.sh
source ./architecture/scripts/provision/9-composer.sh

export DEBIAN_FRONTEND=dialog
