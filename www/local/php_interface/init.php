<?php

unset($_SERVER['PHP_AUTH_USER']);

include 'lib/bitrix_composer.php';

$application = new BitrixComposer();
$application->run();
