# Install

## Headless Configuration

Download and launch **Raspberry Pi Imager**: https://www.raspberrypi.com/software/

### Flash the SD card

1. **Raspberry Pi Device**: Raspberry Pi 3
2. **Operating System**: Raspberry Pi OS (other) → Raspberry Pi OS Lite (64-bit) — Trixie (Debian 13)
3. **Storage**: select the SD card
4. Click **Next** → **Edit Settings**

### Advanced Options

**General tab:**

| Field | Value |
|---|---|
| Set hostname | your hostname |
| Username | your username |
| Password | your password |
| Configure wireless LAN | ✅ SSID, password, Country code |
| Locale settings | Timezone, Keyboard layout |

**Services tab:**

| Field | Value |
|---|---|
| Enable SSH | ✅ Allow public key authentication |
| Authorized public key | paste your public key (`~/.ssh/id_*.pub`) |

→ **Save** → **Yes** → **Yes** (write to card)

### First boot

1. Insert the SD card in the Pi and power it on
2. Wait ~2 minutes
3. Connect via SSH: `ssh <username>@<hostname>.local`

## Linky Dial (Port COM)

Install the Hardware

* Connect the Teleinfo USB to your Raspberry Pi
* Connect your Linky to the Teleinfo USB (using a phone cable or a network cable)

Install the Packages

```bash
sudo apt-get -y install picocom
sudo usermod -a -G dialout www-data
```

Test

```bash
sudo -u www-data picocom -b 1200 -d 7 -p e /dev/ttyUSB0
```

to exit : CTRL + A then CTRL + X

## Project

### Sudoers

Allow your user to run commands as `www-data` without a password (required for deployment scripts):

```bash
echo "<your-username> ALL=(www-data) NOPASSWD: ALL" | sudo tee /etc/sudoers.d/<your-username>-www-data
sudo chmod 440 /etc/sudoers.d/<your-username>-www-data
```

### Packages

Install some useful packages

```bash
sudo apt-get -y install sudo lsb-release inetutils-ping curl vim aptitude ca-certificates bash-completion
sudo apt-get -y install less lsof rsync net-tools screen ssl-cert strace tcpdump telnet
sudo apt-get -y install cron file unzip apt-transport-https tar wget zip
```

### Git

Install GIT

```bash
sudo apt-get -y install git
vim ~/.gitconfig
```

Configure this file like you want.

Then clone the project

```bash
cd /var
sudo chown <your-username>:root www
cd ./www
git clone git@github.com:spipu/linky-reader.git ./linky-reader
sudo rm -rf /var/www/html
cd ~/
```

### PHP

Install PHP

```bash
curl -sSL https://packages.sury.org/php/README.txt | sudo bash -
sudo apt-get update

sudo apt-get -y install \
  libapache2-mod-php8.3 php8.3-cli \
  php8.3-bcmath php8.3-common php8.3-curl php8.3-gd \
  php8.3-iconv php8.3-intl php8.3-mbstring php8.3-mysql \
  php8.3-pdo php8.3-pdo-mysql php8.3-readline \
  php8.3-simplexml php8.3-xml php8.3-xsl php8.3-zip
```

Configure PHP - CLI

```bash
sudo vim /etc/php/8.3/cli/conf.d/99-provision.ini
```

```ini
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
sudo vim /etc/php/8.3/apache2/conf.d/99-provision.ini
```

```ini
same content
```

Configure Apache2 - Virtualhost

```bash
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod rewrite
sudo vim /etc/apache2/sites-available/website.conf
```

```apacheconf
<VirtualHost *:80>
    SetEnv APP_ENV prod

    AddDefaultCharset Off
    AddType 'text/html; charset=UTF-8' html

    DocumentRoot "/var/www/linky-reader/website/public"
    DirectoryIndex index.php

    <Directory "/var/www/linky-reader/website/public">
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Allow from All

        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-l
        RewriteRule .* index.php [QSA,L]
    </Directory>

    <Directory ~ "/var/www/linky-reader/website/public/(bundles|media)/">
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Allow from All

        <FilesMatch .*\.(ico|jpg|jpeg|png|gif|svg|js|css|swf|eot|ttf|otf|woff|woff2)$>
            Header append Cache-Control public
        </FilesMatch>

        <FilesMatch .*\.(zip|gz|gzip|bz2|csv|xml)$>
            Header append Cache-Control no-store
        </FilesMatch>

        <FilesMatch "\.(ph(p[3457]?|t|tml)|[aj]sp|p[ly]|sh|cgi|shtml?|html?)$">
            SetHandler None
            ForceType text/plain
        </FilesMatch>
    </Directory>

    LogLevel warn
    ErrorLog /var/log/apache2/linky-reader-error.log
    CustomLog /var/log/apache2/linky-reader-access.log combined
</VirtualHost>
```

```bash
sudo rm /etc/apache2/sites-enabled/*
sudo ln -s /etc/apache2/sites-available/website.conf /etc/apache2/sites-enabled/website.conf
sudo apache2ctl -S
```

Restart Apache 2

```bash
sudo systemctl restart apache2
sudo systemctl enable apache2
sudo systemctl status apache2
```

### MySQL

Install MySQL

```bash
sudo apt-get install mariadb-server
```

Configure MySQL

```bash
sudo mkdir -p /var/log/mysql
sudo vim /etc/mysql/mariadb.conf.d/provision.cnf
```

```ini
[mysqld]

# Charset
character-set-server = utf8
collation-server = utf8_general_ci

# InnoDB - Other settings
innodb_flush_log_at_trx_commit = 1
innodb_file_per_table = 1

# Network and DNS resolution settings
bind-address = 127.0.0.1
port = 3306
skip-name-resolve

# Slow query Log settings
slow_query_log = 1
slow_query_log_file = /var/log/mysql/mysql-slow.log
long_query_time = 10
```

Restart MySQL

```bash
sudo systemctl restart mysql
sudo systemctl enable mysql
sudo systemctl status mysql
```

Create the app database and user

```bash
sudo mysql
```

```mysql
CREATE DATABASE IF NOT EXISTS `linky-reader`;
CREATE USER IF NOT EXISTS 'linky-reader'@'localhost' IDENTIFIED BY '<your-password>';
GRANT USAGE ON *.* TO 'linky-reader'@'localhost';
GRANT ALL PRIVILEGES ON `linky-reader`.* TO 'linky-reader'@'localhost' WITH GRANT OPTION;
```

Test the user

```bash
mysql -h localhost -u linky-reader -p linky-reader
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

```ini
DATABASE_URL=mysql://linky-reader:<your-password>@localhost:3306/linky-reader?serverVersion=mariadb-11.8.6
MAILER_DSN=native://default

APP_ENV=prod
APP_SECRET=<your-app-secret>
APP_ENCRYPTOR_KEY_PAIR=<your-encryptor-key>
```

Generate `APP_SECRET`:

```bash
sudo -u www-data php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"
```

Generate `APP_ENCRYPTOR_KEY_PAIR`:

```bash
cd /var/www/linky-reader/website
sudo -u www-data php bin/console spipu:encryptor:generate-key-pair
```

Configure the var folder

```bash
cd /var/www/linky-reader/website

mkdir -p ./var
sudo chown <your-username>:www-data ./var
chmod 775 ./var
```

Install the application

```bash
cd /var/www/linky-reader
./architecture/update-app.sh
```

Now you can test the web interface, it should display that the Log File is missing.

The default username is `admin` and the default password is `password`.
You must change it on the first login.

## Test

You can test manually the Linky dial with this command

```bash
sudo -u www-data /var/www/linky-reader/website/bin/console app:linky:read
```

If all is working fine, you can add it to the crontab of the `www-data` user.

```bash
sudo -u www-data crontab /var/www/linky-reader/website/config/crontab
```

After one minute, you will see the log file in the web interface.

That's all!

## Push to a server

You can push the data to an external server, you have just to configure it in the admin panel.
