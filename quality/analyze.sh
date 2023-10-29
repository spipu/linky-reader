#!/bin/bash

# Get the main folder
MAIN_FOLDER="${BASH_SOURCE[0]}"
MAIN_FOLDER="$( realpath "${MAIN_FOLDER}")"
MAIN_FOLDER="$( dirname "${MAIN_FOLDER}")"
MAIN_FOLDER="$( dirname "${MAIN_FOLDER}")"

# Go into the project folder
cd "${MAIN_FOLDER}/website/"

# Prepare the build folder
LOG_FOLDER="${MAIN_FOLDER}/quality/build"
mkdir -p $LOG_FOLDER

# Prepare the bin folder
BIN_FOLDER="${MAIN_FOLDER}/quality/bin"
rm -rf $BIN_FOLDER
mkdir -p $BIN_FOLDER

# Install PHPCPD
wget https://phar.phpunit.de/phpcpd.phar -O "${BIN_FOLDER}/phpcpd.phar" -q
chmod +x "${BIN_FOLDER}/phpcpd.phar"
ln -fs "${BIN_FOLDER}/phpcpd.phar" "${MAIN_FOLDER}/website/vendor/bin/phpcpd"

# Install PHPLOC
wget https://phar.phpunit.de/phploc.phar -O "${BIN_FOLDER}/phploc.phar" -q
chmod +x "${BIN_FOLDER}/phploc.phar"
ln -fs "${BIN_FOLDER}/phploc.phar" "${MAIN_FOLDER}/website/vendor/bin/phploc"

# Configure PHPCS
./vendor/bin/phpcs --config-set php_version 70433

# Tests - PHPQA
./vendor/bin/phpqa \
    --analyzedDirs "src" \
    --ignoredDirs "vendor,Tests" \
    --tools "phpmetrics,phploc,pdepend,phpcs:0,phpmd:0,phpcpd:0,parallel-lint:0" \
    --config "./" \
    --buildDir "${LOG_FOLDER}/" \
    --report "offline" \
    --execution "no-parallel"

# Output
firefox "${LOG_FOLDER}/phpqa.html"
