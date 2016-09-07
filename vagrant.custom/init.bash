#!/usr/bin/env bash

# Скрипт, занимающийся установкой площадки

## Установка БД
mysql -uroot < /home/bitrix/vagrant.custom/mysql.sql

## Разворачивание дампа
mysql -uwww -pwww www < /home/bitrix/vagrant.custom/dump/dump-init.sql
mysql -uwww -pwww www < /home/bitrix/vagrant.custom/dump/dump.sql

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

