#!/bin/bash

if [[ ! -d "/home/$ENV_USER" ]]; then
    showMessage " > Delivery User - Create"

    useradd -g www-data -s /bin/bash -m $ENV_USER

    sed -i "s/#force_color_prompt/force_color_prompt/g" /home/$ENV_USER/.bashrc
    sed -i "s/#alias/alias/g" /home/$ENV_USER/.bashrc

    mkdir -p /home/$ENV_USER/.ssh/
    cp /root/.ssh/authorized_keys /home/$ENV_USER/.ssh/authorized_keys
    chmod 700 /home/$ENV_USER/.ssh
    chmod 600 /home/$ENV_USER/.ssh/authorized_keys
    chown -R $ENV_USER:www-data /home/$ENV_USER/.ssh

    mkdir -p /var/www
    chown -R $ENV_USER:root /var/www
    chmod -R 644 /var/www
    chmod -R +X /var/www
fi

showMessage " > Delivery User - acl"

mkdir -p /etc/sudoers.d/
ALLOWED_SERVICES='/bin/systemctl reload apache2.service'
sh -c "echo 'Cmnd_Alias  DELIVERYSERVICEALLOWED=$ALLOWED_SERVICES'  >  /etc/sudoers.d/$ENV_USER"
sh -c "echo 'Runas_Alias DELIVERYSERVERACCOUNTS=www-data'           >> /etc/sudoers.d/$ENV_USER"
sh -c "echo '$ENV_USER ALL=(DELIVERYSERVERACCOUNTS) NOPASSWD: ALL'  >> /etc/sudoers.d/$ENV_USER"
sh -c "echo '$ENV_USER ALL=(root) NOPASSWD: DELIVERYSERVICEALLOWED' >> /etc/sudoers.d/$ENV_USER"
chmod 440 /etc/sudoers.d/$ENV_USER
chown root:root /etc/sudoers.d/$ENV_USER
