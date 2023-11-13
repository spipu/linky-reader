#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

source ./architecture/conf/env.sh

MYSQL_CMD="mysql"

function createUserAndDatabase() {
    MYSQL_HOST="$1"
    MYSQL_USER="$2"
    MYSQL_PASS="$3"
    MYSQL_DB="$4"

    echo "  - '$MYSQL_USER'@'$MYSQL_HOST'"

    $MYSQL_CMD -e "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DB\`;"
    $MYSQL_CMD -e "CREATE USER IF NOT EXISTS '$MYSQL_USER'@'$MYSQL_HOST' IDENTIFIED BY '$MYSQL_PASS';"
    $MYSQL_CMD -e "GRANT USAGE ON *.* TO '$MYSQL_USER'@'$MYSQL_HOST';"
    $MYSQL_CMD -e "GRANT ALL PRIVILEGES ON \`$MYSQL_DB\`.* TO '$MYSQL_USER'@'$MYSQL_HOST' WITH GRANT OPTION;"
}

DB_USER="linky-reader"
DB_PASS="linky-reader"
DB_NAME="linky-reader"

createUserAndDatabase "localhost" "$DB_USER" "$DB_PASS" "$DB_NAME"
