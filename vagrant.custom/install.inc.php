<?php

use Bitrix\Main\Diag\IExceptionHandlerOutput;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class InstallWizardException extends RuntimeException
{
    protected static function format (array $error)
    {
        return $error[1] ? sprintf('[%d] %s', $error[1], $error[0]) : $error[0];
    }

    public static function check(CWizardStep $wizardStep)
    {
        $errors = $wizardStep->GetErrors();
        if (count($errors))
        {
            foreach ($errors as &$error)
            {
                $error = static::format($error);
            }
            throw new static(implode("\n", $errors));
        }
    }
}

class ExceptionHandlerOutput implements IExceptionHandlerOutput
{
    public function renderExceptionMessage($exception, $debug = false)
    {
        echo ExceptionHandlerFormatter::format($exception, false);
    }
}

class CreateModulesStepExt extends CreateModulesStep
{
    function SendResponse($response)
    {
        echo "------------------------\n";
        echo $response,"\n";
        echo "------------------------\n";
        $this->InstallModule('main', 'utf8');
        $this->InstallModule('main', 'files');
        $this->InstallModule('main', 'database');
        $this->InstallModule('bitrixcloud', 'utf8');
        $this->InstallModule('clouds', 'utf8');
        $this->InstallModule('compression', 'utf8');
        $this->InstallModule('fileman', 'utf8');
        $this->InstallModule('fileman', 'files');
        $this->InstallModule('fileman', 'database');
        $this->InstallModule('highloadblock', 'utf8');
        $this->InstallModule('highloadblock', 'files');
        $this->InstallModule('highloadblock', 'database');
        $this->InstallModule('iblock', 'utf8');
        $this->InstallModule('iblock', 'files');
        $this->InstallModule('iblock', 'database');
        $this->InstallModule('perfmon', 'utf8');
        $this->InstallModule('search', 'utf8');
        $this->InstallModule('seo', 'utf8');
        $this->InstallModule('socialservices', 'utf8');
        $this->InstallModule('translate', 'utf8');
    }
}
