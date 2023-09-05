# Install

## OS

Download Raspberry OS Imager

https://www.raspberrypi.com/software/

Prepare a SD card with a Debian 11 Bullseye LITE

Install it on your Raspberry PI

## Network

```bash
sudo raspi-config
```

* System Options
* Wireless LAN

## SSH

Install the packages

```bash
sudo apt update
sudo apt -y upgrade
sudo apt -y install ssh vim
```

Enable SSH

```bash
sudo vim /etc/ssh/sshd_config
```

* port 22
* PasswordAuthentication yes

```bash
sudo systemctl restart ssh
```

From now, do all using SSH, with your personal ssh key, it will be easiest for all the next steps.

## Linky Dial (Port COM)

Install the Hardware

* Connect the Teleinfo USB to your raspberry
* Connect your linky to the Teleinfo (using a phone or a network cable for example)

Install the Packages

```bash
sudo apt -y install apache2 picocom
sudo usermod -a -G dialout www-data
```

Test

```bash 
sudo -u www-data picocom -b 1200 -d 7 -p e -f n /dev/ttyUSB0
```

to exit : CTRL + A then CTRL + X

## Project

### Packages

Install some useful packages

```bash
sudo apt -y install sudo lsb-release inetutils-ping curl vim aptitude ca-certificates bash-completion
sudo apt -y install less lsof rsync net-tools screen ssl-cert strace tcpdump telnet
sudo apt -y install file unzip ntp acpid iotop dstat apt-transport-https tar wget zip
```

### Git

Install GIT

```bash
sudo apt -y install git
vim ~/.gitconfig
```

Configure this file like you want.

Then clone the project

```bash
cd /var
sudo chown xxxxx.root www
cd ./www
git clone git@github.com:spipu/linky-reader.git ./linky-reader
sudo rm -rf /var/www/html
sudo ln -s /var/www/linky-reader/website/public /var/www/html
cd ~/
```

### PHP

Install PHP

```bash
sudo apt -y install \
  libapache2-mod-php7.4 php7.4-cli \
  php7.4-bcmath php7.4-curl php7.4-iconv php7.4-intl php7.4-json php7.4-mbstring \
  php7.4-readline php7.4-simplexml php7.4-xml php7.4-xsl php7.4-zip \
  php7.4-mysql php7.4-pdo php7.4-pdo-mysql

sudo a2enmod rewrite
```

Configure PHP - CLI

```bash
sudo vim /etc/php/7.4/cli/conf.d/99-provision.ini
```

```ini
always_populate_raw_post_data = -1
date.timezone = Europe/Paris
display_errors = True
error_log =
error_reporting = E_ALL
expose_php = False
log_errors = True
upload_max_filesize = 8M
session.auto_start = 0
```

Configure PHP - Mod Apache

```bash
sudo vim /etc/php/7.4/apache2/conf.d/99-provision.ini
```

```ini
same content
```

Restart Apache 2

```bash
sudo systemctl restart apache2
```

### Composer

Install Composer

```bash
sudo -s
wget -q https://getcomposer.org/composer-stable.phar
mv ./composer-stable.phar /usr/local/bin/composer
chmod 775 /usr/local/bin/composer
exit
composer --version
```

### Finalize project

Configure the application

```bash
vim /var/www/linky-reader/website/.env.local
```

```init
APP_ENV=prod
APP_SECRET=xxxx

ENERGY_API_URL=https://external-website.fr/api
ENERGY_API_NAME=XXXXXXXXX
ENERGY_API_KEY=YYYYYYYY
```

You must generate the secret, and you must have an external server that will be able to receive the data.

Configure the var folder

```bash
cd /var/www/linky-reader/website

mkdir -p ./var
sudo chown xxxxx.www-data ./var
chmod 775 ./var
```

Install packages

```bash
cd /var/www/linky-reader/website

sudo rm -rf ./var/*

composer install

sudo rm -rf ./var/*
```

Now you can test the web interface, it should display that the Log File is missing.

## Test

You can test manually the Linky dial with this command

```bash
sudo -u www-data /var/www/linky-reader/website/bin/console app:linky:read
```

If all is working fine, you can add it to the crontab of the `www-data` user.

```bash
sudo -u www-data crontab -e
```

```cronexp
# m h  dom mon dow   command
* * * * * /var/www/linky-reader/website/bin/console app:linky:read > /var/www/linky-reader/website/var/log/cron.log
```

After one minute, you will see the log file in the web interface.

That's all!
