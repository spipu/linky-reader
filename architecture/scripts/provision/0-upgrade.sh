#!/bin/bash

echo " > UPGRADE"

apt-get -qq update          > /dev/null
apt-get -qq -y dist-upgrade > /dev/null
apt-get -qq -y autoremove   > /dev/null
