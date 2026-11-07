#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/provision.sh   "$ENV_TYPE"
$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/createDb.sh    "$ENV_TYPE"
$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/permissions.sh "$ENV_TYPE"

su "$ENV_USER" -c "$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/test.sh \"$ENV_TYPE\""
su "$ENV_USER" -c "$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/install.sh \"$ENV_TYPE\""
su "$ENV_USER" -c "$ENV_FOLDER/$ARCHITECTURE_FOLDER/scripts/update-crontab.sh \"$ENV_TYPE\""
