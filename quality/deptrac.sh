#!/bin/bash

# needed for output: apt-get install graphviz

# Get the main folder
MAIN_FOLDER="${BASH_SOURCE[0]}"
MAIN_FOLDER="$( realpath "${MAIN_FOLDER}")"
MAIN_FOLDER="$( dirname "${MAIN_FOLDER}")"
MAIN_FOLDER="$( dirname "${MAIN_FOLDER}")"

# Go into the project folder
cd "${MAIN_FOLDER}/website/"

# Create the build folder
LOG_FOLDER="${MAIN_FOLDER}/quality/build/"
mkdir -p $LOG_FOLDER

./vendor/bin/deptrac analyse --config-file=./.depfile.mvc.yaml --no-cache

./vendor/bin/deptrac analyse --config-file=./.depfile.mvc.yaml --no-cache --formatter=graphviz-image --output="${LOG_FOLDER}deptrac-mvc.png"

# Output
firefox "${LOG_FOLDER}deptrac-mvc.png"
