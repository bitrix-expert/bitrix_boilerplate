<?php
$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www/';
$_SERVER['PHP_SELF'] = '/index.php';
///
define('install_edition', 'start');
define("B_PROLOG_INCLUDED", true);
ob_start();
$success = include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/install/wizard/wizard.php");
///
ob_end_clean();
unset($wizard);
$wizard = new CWizardBase(str_replace("#VERS#", SM_VERSION, InstallGetMessage("INS_TITLE")), $package = null);
$arSteps = Array("CreateDBStep", "CreateModulesStep", "CreateAdminStep");
$wizard->AddSteps($arSteps); //Add steps
$wizard->SetTemplate(new WizardTemplate);
$wizard->SetReturnOutput();
$content = $wizard->Display();

// @todo: а теперь тут надо подпихивать параметры которые ждёт каждый шаг и протаскивать Битру по ним, и будь что будет


echo $content;