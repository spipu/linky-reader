# linky-reader

## Install

You must buy :

 * RaspBerry PI 3
 * Micro Teleinfo V2.0 USB [link](https://www.tindie.com/products/hallard/micro-teleinfo-v20/)
 
You must install a Rasp Debian 10 Buster, with the following packages :

 * ssh
 * picocom
 * apache2
 * php 7.3 (cli and apache-mod)
 * all classical php extensions
 
Your RaspBerry must be connected to your network (using wifi or cable), and accessible with ssh.

'
## How to

How to test :

* Connect the Teleinfo USB to your raspberry
* Connect your linky to the Teleinfo (using a phone or a network cable for example)
* the user `www-data` must be in the group `dialout` to have read permissions on `/dev/ttyUSB0`
* launch the following command:

```bash 
sudo -u www-data picocom -b 1200 -d 7 -p e -f n /dev/ttyUSB0
```

to exit : CTRL + A then CTRL + X

Then, you can test the php script:

```bash
sudo -u www-data /var/www/linky-reader/website/bin/console app:linky:read
```

## Sources

* http://www.piblo.fr/raspberry-et-linky/
* https://www.jonathandupre.fr/articles/24-logiciel-scripts/208-suivi-consommation-electrique-compteur-edf-linky-avec-raspberry-pi-zero-w/

