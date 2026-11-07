#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

showTitle "Create Database"

MYSQL_CMD="mysql"

function createUserAndDatabase() {
    MYSQL_HOST="$1"
    MYSQL_USER="$2"
    MYSQL_PASS="$3"
    MYSQL_DB="$4"

    showMessage "  - '$MYSQL_USER'@'$MYSQL_HOST'"

    $MYSQL_CMD -e "CREATE USER IF NOT EXISTS '$MYSQL_USER'@'$MYSQL_HOST' IDENTIFIED BY '$MYSQL_PASS';"
    $MYSQL_CMD -e "GRANT USAGE ON *.* TO '$MYSQL_USER'@'$MYSQL_HOST';"
    $MYSQL_CMD -e "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DB\`;"
    $MYSQL_CMD -e "GRANT ALL PRIVILEGES ON \`$MYSQL_DB\`.* TO '$MYSQL_USER'@'$MYSQL_HOST' WITH GRANT OPTION;"
}

createUserAndDatabase "$DB_HOST" "$DB_USER" "$DB_PASS" "$DB_NAME"
#createUserAndDatabase "\%" "$DB_USER" "$DB_PASS" "$DB_NAME"
