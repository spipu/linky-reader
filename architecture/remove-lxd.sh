#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[LXD]==="
lxd-remove
echo ""

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[FINISHED]==="
