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
        if (preg_match("/Post\\('(?<nextStep>[^']+)',\\s*'(?<nextStepStage>[^']*)',\\s*'(?<status>[^']+)'/", $response, $matches))
        {
            $result = array(
                'progress' => 100,
                'status' => mb_convert_encoding(html_entity_decode($matches['status']), CONSOLE_ENCODING, INSTALL_CHARSET),
                'step' => $matches['nextStep'],
                'stepStage' => $matches['nextStepStage'],
            );
            if ($matches['nextStep'] != '__finish')
            {
                if (preg_match('/SetStatus\\(\'(?<progress>[^\']+)\'/', $response, $m))
                    $result['progress'] = $m['progress'];
            }
            echo base64_encode(serialize($result));
        }
        else
        {
            throw new InstallWizardException('Unexpected response: ' . var_export($response, 1));
        }
        die(0);
    }

    public function processInstallation()
    {
        $current = array(
            "step" => "main",
            "stepStage" => "database",
        );
        do
        {
            $response = $this->emulateAjax($current);
            printf('[%d%%] %s' . PHP_EOL, $response['progress'], html_entity_decode($response['status']));
            $current = $response;
        } while ($current['step'] !== '__finish');
    }

    /**
     * Executes another copy of console process to continue
     *
     * @param array $vars
     * @return array
     */
    protected function emulateAjax(array $vars)
    {
        ob_end_flush();
        $proc = popen('php -f ' . implode(' ', $GLOBALS['argv']) . ' ' . escapeshellarg(base64_encode(serialize($vars))) . ' 2>&1', 'r');
        $output = '';
        while (!feof($proc)) {
            $output .= fread($proc, 4096);
        }
        if (pclose($proc) !== 0)
        {
            throw new InstallWizardException('Child process failed');
        }
        $result = unserialize(base64_decode($output));
        if (!is_array($result))
        {
            throw new InstallWizardException('Unexpected response: ' . var_export($output, 1));
        }
        return $result;
    }
}

class CheckLicenseKeyExt extends CheckLicenseKey
{
    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
        $licenseKey = $wizard->GetVar("license");
        global $DBType;

        $lic_key_variant = $wizard->GetVar("lic_key_variant");

        if((defined("TRIAL_RENT_VERSION") || (defined("TRIAL_VERSION") && $lic_key_variant == "Y")) && strlen($licenseKey) <= 0)
        {
            $lic_key_user_surname = $wizard->GetVar("user_surname");
            $lic_key_user_name = $wizard->GetVar("user_name");
            $lic_key_email = $wizard->GetVar("email");

            $bError = false;

            if(!$bError)
            {
                $lic_site = $_SERVER["HTTP_HOST"];
                if(strlen($lic_site) <= 0)
                    $lic_site = "localhost";

                $arClientModules = Array();
                $handle = @opendir($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules");
                if ($handle)
                {
                    while (false !== ($dir = readdir($handle)))
                    {
                        if (is_dir($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$dir)
                            && $dir!="." && $dir!="..")
                        {
                            $module_dir = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$dir;
                            if (file_exists($module_dir."/install/index.php"))
                            {
                                $arClientModules[] = $dir;
                            }
                        }
                    }
                    closedir($handle);
                }

                $lic_edition = serialize($arClientModules);

                if (defined("INSTALL_CHARSET") && strlen(INSTALL_CHARSET) > 0)
                    $charset = INSTALL_CHARSET;
                else
                    $charset = "windows-1251";

                if(LANGUAGE_ID == "ru")
                    $host = "www.1c-bitrix.ru";
                else
                    $host = "www.bitrixsoft.com";

                $path = "/bsm_register_key.php";
                $port = 80;
                $query = "sur_name=$lic_key_user_surname&first_name=$lic_key_user_name&email=$lic_key_email&site=$lic_site&modules=".urlencode($lic_edition)."&db=$DBType&lang=".LANGUAGE_ID."&bx=Y&max_users=".TRIAL_RENT_VERSION_MAX_USERS;

                if(defined("install_license_type"))
                    $query .= "&cp_type=".install_license_type;
                if(defined("install_edition"))
                    $query .= "&edition=".install_edition;

                $fp = @fsockopen("$host", "$port", $errnum, $errstr, 30);
                if ($fp)
                {
                    fputs($fp, "POST {$path} HTTP/1.1\r\n");
                    fputs($fp, "Host: {$host}\r\n");
                    fputs($fp, "Content-type: application/x-www-form-urlencoded; charset=\"".$charset."\"\r\n");
                    fputs($fp, "User-Agent: bitrixKeyReq\r\n");
                    fputs($fp, "Content-length: ".(function_exists("mb_strlen")? mb_strlen($query, 'latin1'): strlen($query))."\r\n");
                    fputs($fp, "Connection: close\r\n\r\n");
                    fputs($fp, $query."\r\n\r\n");
                    $page_content = "";
                    $headersEnded = 0;
                    while(!feof($fp))
                    {
                        $returned_data = fgets($fp, 128);
                        if($returned_data=="\r\n")
                        {
                            $headersEnded = 1;
                        }

                        if($headersEnded==1)
                        {
                            $page_content .= htmlspecialcharsbx($returned_data);
                        }
                    }
                    fclose($fp);
                }
                $arContent = explode("\n", $page_content);

                $bEr = false;
                $bOk = false;
                $key = "";
                foreach($arContent as $v)
                {
                    if($v == "ERROR")
                        $bEr = true;
                    elseif($v == "OK")
                        $bOk = true;

                    if(strlen($v) > 10)
                        $key = trim($v);
                }

                if($bOk && strlen($key) >0)
                {
                    $wizard->SetVar("license", $key);
                }
                elseif(defined("TRIAL_RENT_VERSION"))
                    $this->SetError(InstallGetMessage("ACT_KEY_REQUEST_ERROR"), "email");
            }
        }


        $this->CreateLicenseFile();
    }

    function ShowStep()
    {
        $this->content = '';
    }
}
