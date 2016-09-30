﻿#!/usr/bin/env bash

# Скрипт, занимающийся установкой площадки

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/"
HOME="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/.. && pwd )/"

## Установка БД
mysql -uroot < ${HOME}/vagrant.custom/mysql.sql || exit 1

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
cd ${HOME}
wget http://www.1c-bitrix.ru/download/start_encode_php5.tar.gz || exit 1

mkdir tmp_b
tar -xf start_encode_php5.tar.gz -C tmp_b
mv tmp_b/bitrix www/bitrix
mv tmp_b/upload www/upload
rm tmp_b -rf
rm start_encode_php5.tar.gz -rf

## Запускаем установку Битрикса. К сожалению - приходится извращаться и запускать по шагам.
php -f ${SCRIPT_DIR}install.php || exit 1

echo "OLALA!"
exit 1; # @todo: когда победим установку битрикса - добить остальное!

## Обновление композера
cd ${HOME}
composer install

## Временный костыль
# @notice Пока что console jedi не умеет прописывать файлы `.settings.php` и `dbconn.php`, поэтому нужно скопировать
cp -f ${HOME}www/bitrix/.settings.php.example ${HOME}www/bitrix/.settings.php
cp -f ${HOME}www/bitrix/php_interface/dbconn.php.example ${HOME}www/bitrix/php_interface/dbconn.php

## Init Console.Jedi
cd ${HOME}
# @notice: на проекте уже выполнен `www/vendor/bin/jedi init`
www/vendor/bin/jedi env:init dev

