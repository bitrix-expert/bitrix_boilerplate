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
    public static function renderExceptionMessage($exception, $debug = false)
    {
        echo ExceptionHandlerFormatter::format($exception, false);
    }
}

class CreateModulesStepExt extends CreateModulesStep
{
    function SendResponse($response)
    {
        if (preg_match("/SetStatus\\('(?<progress>[^']+)'.*?Post\\('(?<nextStep>[^']+)',\\s*'(?<nextStepStage>[^']+)',\\s*'(?<status>[^']+)'/", $response, $matches))
        {
            printf('[%d%%] %s' . PHP_EOL, $matches['progress'], html_entity_decode($matches['status']));
            $this->restartScript(array(
                'nextStep' => $matches['nextStep'],
                'nextStepStage' => $matches['nextStepStage'],
            ));
        }
        else
        {
            throw new InstallWizardException('Unexpected response: ' . var_export($response, 1));
        }
        die();
    }

    /**
     * Executes another copy of console process to continue
     *
     * @param array $vars
     * @return int
     */
    protected function restartScript(array $vars)
    {
        ob_end_flush();
        $proc = popen('php -f ' . implode(' ', $GLOBALS['argv']) . ' ' . escapeshellarg(base64_encode(json_encode($vars))) . ' 2>&1', 'r');
        while (!feof($proc)) {
            echo fread($proc, 4096);
        }

        return pclose($proc);
    }
}
