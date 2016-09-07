<?php

use Bitrix\Main\Application;

class BitrixComposer
{
    public function __construct()
    {
        $autoload = Application::getDocumentRoot() . '/../vendor/autoload.php';

        if (file_exists($autoload))
        {
            include_once $autoload;
        }
    }

    /**
     * Запуск приложения.
     */
    public function run()
    {
    }
}