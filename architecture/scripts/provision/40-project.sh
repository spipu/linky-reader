#!/bin/bash

if [[ "$ENV_CODE" = "dev" ]]; then
    showMessage " > Dev Scripts"

    rm -rf "/home/${ENV_USER}/mysql.sh"
    ln -s "${ENV_FOLDER}/${ARCHITECTURE_FOLDER}/scripts/mysql.sh" "/home/${ENV_USER}/mysql.sh"

    rm -rf "/home/${ENV_USER}/install.sh"
    ln -s "${ENV_FOLDER}/${ARCHITECTURE_FOLDER}/scripts/install.sh" "/home/${ENV_USER}/install.sh"

    rm -rf "/home/${ENV_USER}/website"
    ln -s "${ENV_FOLDER}/website" "/home/${ENV_USER}/website"
fi
