create database if not exists www default charset utf8 collate utf8_unicode_ci;
grant all on www.* to www@'%' identified by 'www';
grant all on www.* to www@'localhost' identified by 'www';
