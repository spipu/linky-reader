#!/bin/bash

showMessage " > Packages - Install"

apt-get -qq -y install \
    sudo lsb-release inetutils-ping curl vim aptitude ca-certificates bash-completion \
    less lsof moreutils patch rsync net-tools screen ssl-cert strace tcpdump telnet \
    cron file unzip apt-transport-https tar wget zip tzdata \
    > /dev/null

showMessage " > Timezone - Configure"

APP_TIMEZONE="Europe/Paris"

if [[ "$ENV_TYPE" = "docker" ]]; then
  ln -fs "/usr/share/zoneinfo/${APP_TIMEZONE}" /etc/localtime
  dpkg-reconfigure -f noninteractive tzdata
else
  timedatectl set-timezone "${APP_TIMEZONE}"
fi
