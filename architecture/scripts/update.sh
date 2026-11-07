#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_TYPE="none"
ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

showTitle "Update"

cd "${ENV_FOLDER}/${WEB_FOLDER}"

showMessage "Composer"
composer update

showMessage "End"

../$ARCHITECTURE_FOLDER/scripts/install.sh
