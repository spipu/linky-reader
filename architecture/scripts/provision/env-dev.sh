#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../../

source ./$ARCHITECTURE_FOLDER/scripts/provision/00-delivery-user.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/02-upgrade.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/03-packages.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/10-php.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/11-apache.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/12-mysql.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/20-composer.sh
source ./$ARCHITECTURE_FOLDER/scripts/provision/40-project.sh
