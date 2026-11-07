#!/bin/bash

set -e

CURRENT_SCRIPT=$(readlink -f "${BASH_SOURCE[0]}")
ARCHITECTURE_FOLDER=$(basename "$(dirname "$(dirname "$CURRENT_SCRIPT")")")

cd "$(dirname "$CURRENT_SCRIPT")"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./$ARCHITECTURE_FOLDER/scripts/include/init.sh

showTitle "Provision"

export DEBIAN_FRONTEND=noninteractive
export LC_ALL=C

source ./$ARCHITECTURE_FOLDER/scripts/provision/env-${ENV_CODE}.sh

export DEBIAN_FRONTEND=dialog
