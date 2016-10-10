#!/usr/bin/env bash

#
#   Данный скрипт запускается на каждой инициации машины
#

## Чиним косяки Bitrix.Env - почему-то httpd не стартует сам
sudo service httpd restart
