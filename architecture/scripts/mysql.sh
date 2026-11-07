#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

if [[ "$2" ]]; then
  mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "$2"
else
  mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
fi
