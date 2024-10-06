<?php
define('DEBUG_FILE_NAME', $_SERVER["DOCUMENT_ROOT"] .'/logs/OTUS_test.log');
include_once __DIR__ . '/../app/autoload.php';

include_once __DIR__ . '/classes/BXHelper.php';


if (file_exists(__DIR__ . '/classes/autoload.php')) {
    require_once __DIR__ . '/classes/autoload.php';
}
BXHelper::addLog('TEststsetse');
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


use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

// пользовательский тип для свойства инфоблока
$eventManager->AddEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    [
        'UserTypes\IBLink', // класс обработчик пользовательского типа свойства
        'GetUserTypeDescription'
    ]
);

// пользовательский тип для UF поля
$eventManager->AddEventHandler(
    'main',
    'OnUserTypeBuildList',
    [
        'UserTypes\FormatTelegramLink', // класс обработчик пользовательского типа UF поля
        'GetUserTypeDescription'
    ]
);


Loader::registerAutoLoadClasses(null, [
    'UserTypes\BookingProceduresProperty' => '/local/php_interface/include/UserTypes/BookingProceduresProperty.php',
]);

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    [
        'UserTypes\BookingProceduresProperty',
        'GetUserTypeDescription'
    ]
);


