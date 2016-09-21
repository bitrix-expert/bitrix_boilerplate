<?php define('install_edition', 'start');?><?php

$strErrorMessage = "";

// First compatibility check
if (!isset($_SERVER["DOCUMENT_ROOT"]) || strlen($_SERVER["DOCUMENT_ROOT"])<=0 || !file_exists($_SERVER["DOCUMENT_ROOT"]) || !is_dir($_SERVER["DOCUMENT_ROOT"]))
	$strErrorMessage .= '<b>$_SERVER["DOCUMENT_ROOT"]</b> variable must be set to the document root directory under which the current script is executing.<br />';
elseif (!file_exists($_SERVER["DOCUMENT_ROOT"]."/.access.php"))
	$strErrorMessage .= 'The file <b>.access.php</b> is not found in the site root. Apparently the installation package has been unpacked incorrectly.<br />';

if ($_SERVER['PHP_SELF'] != "/index.php")
	$strErrorMessage .= 'Bitrix site manager must be installed in web server root directory.<br />';

if (!ini_get("short_open_tag"))
	$strErrorMessage .= '<b>short_open_tag</b> value must be turned on in you <b>php.ini</b> or <b>.htaccess</b> file.<br />';

if (strlen($strErrorMessage) > 0)
	die('<font color="#FF0000">'.$strErrorMessage."<br />Please modify the server's configuration or contact administrator of your hosting.</font>");

define("B_PROLOG_INCLUDED", true);

$success = include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/install/wizard/wizard.php");
if (!$success)
	die('<font color="#FF0000">Folder /bitrix/ is inaccessible for writing and/or reading</font>');
?>