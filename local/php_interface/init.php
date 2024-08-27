<?php
define('DEBUG_FILE_NAME', $_SERVER["DOCUMENT_ROOT"] .'/logs/OTUS_test.log');

if (file_exists(__DIR__ . '/classes/autoload.php')) {
    require_once __DIR__ . '/classes/autoload.php';
}

//\Otus\Diagnostic\OtusNewHelper::writeToLog('Hello, world!', 'test');

try {
    throw new \Exception('Hello, world!'); // Создаем исключение с сообщением
} catch (\Exception $e) {
    \Otus\Diagnostic\OtusNewHelper::writeToLog($e, 'test');
}