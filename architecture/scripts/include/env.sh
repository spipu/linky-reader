#!/bin/bash

# load the list of the available environment
ENVIRONMENTS=()
for entry in ./$ARCHITECTURE_FOLDER/conf/env_*.sh
do
    code=$(basename "$entry" | cut -d '_' -f 2 | cut -d '.' -f 1)
    if [[ "$code" != "all" ]]; then
        ENVIRONMENTS+=("$code")
    fi
done

# The env is required
if [[ ! "$ENV_TYPE" ]]; then
    if [[ ! "$1" ]]; then
        showMessage "Available environments:"
        for code in "${ENVIRONMENTS[@]}"; do
            showMessage " - $code"
        done
        showError "You must provide a environment"
        exit 1
    fi

    ENV_TYPE="$1"
fi

# Display error if the environment is unknown
if [[ "${ENV_TYPE}" != "none" ]]; then
    arrayIn "${ENV_TYPE}" "${ENVIRONMENTS[@]}"
    if [[ $? = 1 ]]; then
        showError "No environment ${ENV_TYPE}"
        exit 1
    fi
fi

# Global Parameters
source ./$ARCHITECTURE_FOLDER/conf/env_all.sh
ENV_ARCHITECTURE="amd64"
DB_HOST="localhost"

# Local Parameters (for docker, because it can depend on the host OS)
LOCAL_FILE="./$ARCHITECTURE_FOLDER/conf/env.local.sh"
if [[ ! -f "${LOCAL_FILE}" ]]; then
    echo "#!/bin/bash"                  >  ${LOCAL_FILE}
    echo ""                             >> ${LOCAL_FILE}
    echo "# Docker Parameters"          >> ${LOCAL_FILE}
    echo "ENV_DOCKER_IP=\"127.0.50.1\"" >> ${LOCAL_FILE}
    echo "ENV_DOCKER_PORT_START=\"0\""  >> ${LOCAL_FILE}
    echo "ENV_ARCHITECTURE=\"amd64\""   >> ${LOCAL_FILE}
    echo "DB_HOST=\"localhost\""        >> ${LOCAL_FILE}
fi
source ${LOCAL_FILE}

# We must provide a Env Type
if [[ -z "${ENV_TYPE}" ]]; then
    showError "ERROR - You must provide an Environment Type"
    exit 1
fi

# If it is not "none" => load the env file
if [[ "${ENV_TYPE}" != "none" ]]; then
    ENV_FILE="./$ARCHITECTURE_FOLDER/conf/env_${ENV_TYPE}.sh"
    if [[ ! -f "${ENV_FILE}" ]]; then
        showError "ERROR - The Environment File \"${ENV_FILE}\" does not exist"
        exit 1
    fi

    source ${ENV_FILE}

    # Build the VM folder automatically
    if [[ -z "${ENV_DO_NOT_GENERATE}" ]]; then
        rm -rf ./$ARCHITECTURE_FOLDER/vm
        cp -r  ./$ARCHITECTURE_FOLDER/conf/template/vm ./$ARCHITECTURE_FOLDER/vm

        FILES=`find ./$ARCHITECTURE_FOLDER/vm/ -type f`
        for FILE in ${FILES}
        do
            remplaceVariablesInFile "$FILE"
            remplaceVariableInFile "$FILE" "ENV_DOCKER_IP"           "$ENV_DOCKER_IP"
            remplaceVariableInFile "$FILE" "ENV_DOCKER_PORT_HTTP"    "$(($ENV_DOCKER_PORT_START+80))"
            remplaceVariableInFile "$FILE" "ENV_DOCKER_PORT_HTTPS"   "$(($ENV_DOCKER_PORT_START+443))"
            remplaceVariableInFile "$FILE" "ENV_DOCKER_PORT_SSH"     "$(($ENV_DOCKER_PORT_START+22))"
            remplaceVariableInFile "$FILE" "ENV_DOCKER_PORT_MAILDEV" "$(($ENV_DOCKER_PORT_START+1080))"
        done

        # Specific - LXD Hosts
        vmFile="./$ARCHITECTURE_FOLDER/vm/lxdfile"
        for subHost in ${ENV_HOST_SUB_HOSTS[@]}; do
            remplaceVariableInFile "${vmFile}" "ENV_SUB_HOSTS" "host=$subHost\n{{ENV_SUB_HOSTS}}"
        done
        remplaceVariableInFile "${vmFile}" "ENV_SUB_HOSTS" ""
        sed -i 's/\\n/\n/g' "${vmFile}"
    fi
fi

CONFIG_FOLDER="${PWD}/$ARCHITECTURE_FOLDER/conf/template"
MAIN_FOLDER="${ENV_FOLDER}/${WEB_FOLDER}"
