#!/usr/bin/env bash

# Скрипт, занимающийся установкой площадки

## Установка БД
mysql -uroot < /home/bitrix/vagrant.custom/mysql.sql

# Установка Битрикс
# Попытка сделать то, что делает скрипт http://www.1c-bitrix.ru/download/scripts/bitrixsetup.php

# @todo: нужно сделать какой-то выбор типа редакции
#http://www.1c-bitrix.ru/private/download/
#                "business"=>"Бизнес",
#                "expert"=>"Эксперт",
#                "small_business"=>"Малый бизнес",
#                "standard"=>"Стандарт",
#                "start"=>"Старт",
#_encode_php5.tar.gz


cd /home/bitrix
wget http://www.1c-bitrix.ru/download/start_encode_php5.tar.gz
mkdir tmp_b
tar -xf start_encode_php5.tar.gz -C tmp_b
mv tmp_b/bitrix www/bitrix
mv tmp_b/upload www/upload
php /home/bitrix/vagrant.custom/install.php

mv tmp_b/index.php www/index.php ### ОТЧАЯННО ХОЧЕТСЯ ЭТУ ГАДОСТЬ ОБОЙТИ, ИБО ТАМ УСТАНОВКА ИДЁТ ЧЕРЕЗ ВИЗАРД, А НЕ ЧЕРЕЗ КОНСОЛЬ
rm tmp_b -rf
rm start_encode_php5.tar.gz -rf


## Обновление композера
cd /home/bitrix
composer install

## Временный костыль
# @notice Пока что console jedi не умеет прописывать файлы `.settings.php` и `dbconn.php`, поэтому нужно скопировать
cp -f /home/bitrix/www/bitrix/.settings.php.example /home/bitrix/www/bitrix/.settings.php
cp -f /home/bitrix/www/bitrix/php_interface/dbconn.php.example /home/bitrix/www/bitrix/php_interface/dbconn.php

## Init Console.Jedi
cd /home/bitrix
# @notice: на проекте уже выполнен `www/vendor/bin/jedi init`
www/vendor/bin/jedi env:init dev

