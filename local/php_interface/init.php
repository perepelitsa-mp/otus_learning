<?php
define('DEBUG_FILE_NAME', $_SERVER["DOCUMENT_ROOT"] .'/logs/OTUS_test.log');
include_once __DIR__ . '/../app/autoload.php';

if (file_exists(__DIR__ . '/classes/autoload.php')) {
    require_once __DIR__ . '/classes/autoload.php';
}

//\Otus\Diagnostic\OtusNewHelper::writeToLog('Hello, world!', 'test');

try {
    throw new \Exception('Hello, world!'); // Создаем исключение с сообщением
} catch (\Exception $e) {
    \Otus\Diagnostic\OtusNewHelper::writeToLog($e, 'test');
}

function pr($var, $type = false) {
    echo '<pre style="font-size:10px; border:1px solid #000; background:#FFF; text-align:left; color:#000;">';
    if ($type)
        var_dump($var);
    else
        print_r($var);
    echo '</pre>';
}
