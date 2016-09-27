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


## Скачиваем и распаковываем Битрикс
cd /home/bitrix
wget http://www.1c-bitrix.ru/download/start_encode_php5.tar.gz
mkdir tmp_b
tar -xf start_encode_php5.tar.gz -C tmp_b
mv tmp_b/bitrix www/bitrix
mv tmp_b/upload www/upload
rm tmp_b -rf
rm start_encode_php5.tar.gz -rf

## Запускаем установку Битрикса. К сожалению - приходится извращаться и запускать по шагам.
cd /home/bitrix/vagrant.custom/
resl=1
x=1
while [ $resl != 200 ]
do
  php /home/bitrix/vagrant.custom/install.php $x
  resl=`echo $?`
  x=$(( $x + 1 ))
done

echo "OLALA!"
exit 1; # @todo: когда победим установку битрикса - добить остальное!

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

