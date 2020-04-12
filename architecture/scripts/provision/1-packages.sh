#!/bin/bash

echo " > PACKAGES"

apt-get -qq -y install \
    sudo lsb-release inetutils-ping curl vim aptitude ca-certificates bash-completion \
    less lsof moreutils patch rsync net-tools screen ssl-cert strace tcpdump telnet \
    file unzip ntp acpid iotop dstat apt-transport-https tar wget zip > /dev/null
