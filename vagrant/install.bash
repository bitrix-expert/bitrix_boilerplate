#!/usr/bin/env bash

#
#   Данный скрипт установит BitrixVM окружение.
#   В результате работы скрипта папка www должна заполниться дефолтными файлами (bitrixsetup.php, restore.php и прочее)
#   Как с ними распоряжаться - задача следующего сценария
#

sudo yum -y update
sudo yum -y install wget
# @todo: сделать условие - если файлы окружения уже лежат в репозитории - надо брать оттуда. Если нет - тянем из официкальной репы.
wget http://repos.1c-bitrix.ru/yum/bitrix-env.sh
chmod +x bitrix-env.sh
./bitrix-env.sh

## Чиним косяки Bitrix.Env

# Частый косяк из-за неправильного DNS-сервера в офисе
printf "nameserver 8.8.8.8" | sudo tee /etc/resolv.conf > /dev/null

# отрубить автоматический запуск меню для рута
sudo sed -i 's/\/opt/#\/opt/' /root/menu.sh

# Правки, связанные с вагрантом - настройки httpd
sudo sed -i 's/User bitrix/User vagrant/' /etc/httpd/conf/httpd.conf
sudo sed -i 's/Group bitrix/Group vagrant/' /etc/httpd/conf/httpd.conf
sudo service httpd restart
# Правки, связанные с вагрантом - сессии
mkdir /tmp/php_sessions
mkdir /tmp/php_sessions/www
chown vagrant:vagrant /tmp/php_sessions -R

# Включаем phar-файлы
sudo cp /etc/php.d/20-phar.ini.disabled /etc/php.d/20-phar.ini
sudo service httpd restart

# Прокидываем ssh-ключи
if [ ! -f /home/bitrix/vagrant.custom/ssh/id_rsa ]
  then
    tput bold;
    tput setaf 1;
    echo "SSH key is missing (vagrant.custom/ssh/id_rsa)"
    tput sgr0;
    exit 0
  else
    echo "Copy SSH key..."
    mkdir /home/vagrant/.ssh/
    cp -f /home/bitrix/vagrant.custom/ssh/id_rsa /home/vagrant/.ssh/id_rsa
    chmod 0600 /home/vagrant/.ssh/id_rsa
fi

# Открываем доступ к MySQL для IDE
# @todo

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/sbin/composer
