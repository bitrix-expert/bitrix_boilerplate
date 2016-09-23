<?php

use Bitrix\Main\Diag\IExceptionHandlerOutput;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class InstallWizardException extends RuntimeException {}

class ExceptionHandlerOutput implements IExceptionHandlerOutput
{
    public function renderExceptionMessage($exception, $debug = false)
    {
        echo ExceptionHandlerFormatter::format($exception, false);
    }

    protected function formatWizardError (array $error)
    {
        return $error[1] ? sprintf('[%d] %s', $error[1], $error[0]) : $error[0];
    }

    public function checkWizardErrors(CWizardStep $wizardStep)
    {
        $errors = $wizardStep->GetErrors();
        if (count($errors))
        {
            foreach ($errors as &$error)
            {
                $error = $this->formatWizardError($error);
            }
            throw new InstallWizardException(implode("\n", $errors));
        }
    }
}
