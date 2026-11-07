#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

showTitle "Test Services"

showMessage " => PHP"
php -v | grep cli
echo ""

showMessage " => MySQL"
mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -N -e "SHOW DATABASES like \"$DB_NAME\";"
echo ""

showMessage "Finished"
