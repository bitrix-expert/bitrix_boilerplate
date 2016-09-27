<?php
// Из-за особенностей работы скриптов Битрикса - приходится выполнять этот скрипт шаг за шагом
$allSteps = ["CreateDBStep", "CreateModulesStepExt", "CreateAdminStep"];
$step = $argv[1];
if ((!$step) or (!preg_match('/^[0-9]$/',$step))) {
    echo "Sorry, this install script works only step-by-step $step. You need to use integer argument with step number";
    exit(200);
}
if (($step < 1) or ($step > count($allSteps))) {
    echo "steps over";
    exit(200);
}

// includes and defines
use Bitrix\Main\Application;
define('CONSOLE_ENCODING', 'cp866');
define('DEBUG_MODE','Y');
define("LANGUAGE_ID", 'ru');
define("INSTALL_CHARSET", 'utf8');
define('install_edition', 'start');
define("B_PROLOG_INCLUDED", true);
$_SERVER["DOCUMENT_ROOT"] = __DIR__.'/../www/';
$_SERVER['PHP_SELF'] = '/index.php';

// Скрипт инсталляции
ob_start();
$success = include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/install/wizard/wizard.php");
if (!$success)
    die('Folder /bitrix/ is inaccessible for writing and/or reading');
ob_end_clean();
unset($wizard);

// Кастомные классы.
require __DIR__ . '/install.inc.php';

// Главная логика. Установка шаг за шагом.
$arSteps = [$allSteps[$step-1]];
$exceptionHandlerOutput = new ExceptionHandlerOutput();
Application::getInstance()->getExceptionHandler()->setHandlerOutput($exceptionHandlerOutput);
$wizard = new CWizardBase(str_replace("#VERS#", SM_VERSION, InstallGetMessage("INS_TITLE")), $package = null);
$wizard->AddSteps($arSteps); //Add steps
$wizard->SetTemplate(new WizardTemplate);
$wizard->SetReturnOutput();

// Настройки для инсталлятора Битрикс
$vars = array(
    // Настойки базы данных для мастера установки
    'dbType' => 'mysql',
    'user' => 'www',
    'password' => 'www',
    'host' => 'localhost', // port is optional
    'database' => 'www',
    'create_database' => 'N',
    'create_user' => 'N',
    'create_database_type' => '', // empty or innodb
    'root_user' => 'root', // if create_database == 'Y'
    'root_password' => '', // if create_database == 'Y'
    'file_access_perms' => '0644',
    'folder_access_perms' => '0755',
    'utf8' => 'Y',
    'email' => 'team@bitrix.expert',
    'login' => 'admin',
    'admin_password' => 'adminadmin',
    'admin_password_confirm' => 'adminadmin',
    'user_name' => 'Админ',
    'user_surname' => 'Админов'
);
foreach ($vars as $name => $value)
{
    $wizard->SetVar($name, $value);
}

ob_start(function ($content) {
    return mb_convert_encoding($content, CONSOLE_ENCODING, INSTALL_CHARSET);
});

// Погнали устанавливать!
/** @var CWizardStep[] $steps */
$steps = $wizard->GetWizardSteps();
foreach ($steps as $stepName => $step)
{
    echo 'Running installer step ' . $stepName . "...\n";
    $step->OnPostForm();
    InstallWizardException::check($step);
    echo "Step over\n";
}
echo "Install script over\n";
ob_get_flush();

exit(0);