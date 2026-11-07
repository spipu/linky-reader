#!/bin/bash

showMessage " > Packages - Upgrade"

apt-get -qq update          > /dev/null
apt-get -qq -y dist-upgrade > /dev/null
apt-get -qq -y autoremove   > /dev/null
