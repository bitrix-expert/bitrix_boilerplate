<?php

// includes and defines
use Bitrix\Main\Application;
ini_set('output_buffering', false);
// @todo посмотреть какие из этих констант действительно важны
// @todo разделить на настройки нашего установщика и константы битрикса
define('CONSOLE_ENCODING', 'utf8');  // @todo кодировка консоли разная в разных средах. её нужно как-то определять и перекодировать сообщения битрикса в эту кодировку (из INSTALL_CHARSET?)
//define('DEBUG_MODE','Y');
//define("LANGUAGE_ID", 'ru'); //@todo заполняется автоматом из /install.config, можно не определять тут
//define("INSTALL_CHARSET", 'cp1251'); //@todo заполняется автоматом из /install.config, можно не определять тут
define("PRE_LANGUAGE_ID", 'ru'); //@todo используется как LANGUAGE_ID если файла /install.config нет
define("PRE_INSTALL_CHARSET", 'cp1251'); //@todo используется как INSTALL_CHARSET если файла /install.config нет
define('install_edition', 'start');
define("B_PROLOG_INCLUDED", true);
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__.'/../www/');
$_SERVER['PHP_SELF'] = '/index.php';

if (!ini_get("short_open_tag"))
{
    echo 'short_open_tag value must be turned on in you php.ini' . PHP_EOL;
    die(1);
}

// Скрипт инсталляции
ob_start();
$success = include $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/install/wizard/wizard.php";
ob_end_clean();
if (!$success)
{
    printf('Can\'t find /bitrix/ folder or it is inaccessible for writing and/or reading at %s' . PHP_EOL, $_SERVER['DOCUMENT_ROOT']);
    die(1);
}
unset($wizard);

error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_STRICT));
ini_set('display_errors', 1);

// Кастомные классы.
require __DIR__ . '/install.inc.php';

// Главная логика. Установка шаг за шагом.
$exceptionHandlerOutput = new ExceptionHandlerOutput();
Application::getInstance()->getExceptionHandler()->setHandlerOutput($exceptionHandlerOutput);
$wizard = new CWizardBase(str_replace("#VERS#", SM_VERSION, InstallGetMessage("INS_TITLE")), $package = null);
$arSteps = Array("CreateDBStep", "CheckLicenseKeyExt", "CreateModulesStepExt", "CreateAdminStep", "FinishStepExt");
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
    'email' => 'boilerplate@bitrix.expert',
    'login' => 'admin',
    'admin_password' => 'adminadmin',
    'admin_password_confirm' => 'adminadmin',
    'user_name' => 'Админ',
    'user_surname' => 'Админов',
    'lic_key_variant' => 'Y'
);
foreach ($vars as $name => $value)
{
    $wizard->SetVar($name, mb_convert_encoding($value, INSTALL_CHARSET, 'utf-8'));
}

$arg = unserialize(base64_decode(end($GLOBALS['argv'])));
$ajaxEmulation = is_array($arg);

// special case for CreateModules step ajax emulation
if ($ajaxEmulation)
{
    $wizard->SetVar('nextStep', $arg['step']);
    $wizard->SetVar('nextStepStage', $arg['stepStage']);
    $wizard->SetCurrentStep('create_modules');
    /** @var CreateModulesStepExt $currentStep */
    $currentStep = $wizard->GetCurrentStep();
    $currentStep->OnPostForm();
    die(0);
}
else
{
    $wizard->SetCurrentStep($wizard->firstStepID);
}

$setOutputEncodingHandler = function() {
    while (@ob_get_level()) { ob_end_flush(); }
    ob_start(function ($content)
    {
        return mb_convert_encoding($content, CONSOLE_ENCODING, INSTALL_CHARSET);
    });
};

$setOutputEncodingHandler();

// Погнали устанавливать!
/** @var CWizardStep[] $steps */
$steps = $wizard->GetWizardSteps();
while ($step = $wizard->GetCurrentStep())
{
    printf('[%s] %s...' . PHP_EOL, $step->GetStepID(), $step->GetTitle());
    if ($step instanceof CreateModulesStepExt)
    {
        $step->processInstallation();
        $setOutputEncodingHandler();
        $step->nextStepID = 'create_admin';
    }
    else
    {
        if ($step instanceof CreateDBStep && defined('TRIAL_VERSION'))
        {
            $step->nextStepID = 'check_license_key';
        }
        elseif ($step instanceof CreateAdminStep)
        {
            $step->nextStepID = 'finish';
        }
        $step->OnPostForm();
    }
    InstallWizardException::check($step);

    $nextStepId = $step->GetNextStepID();
    if ($nextStepId)
    {
        $wizard->SetCurrentStep($step->GetNextStepID());
        if (defined('DEBUG_MODE'))
        {
            echo "Step over. Next step: {$step->GetNextStepID()}\n";
        }
    }
    else
    {
        break;
    }
}
