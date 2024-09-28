<?php
\Bitrix\Main\Loader::registerAutoloadClasses('mycompany.mycustommodule', [
    'MyCompany\MyCustomModule\MyCustomTable' => 'lib/mycustomtable.php',
    'MyCompany\MyCustomModule\EventHandlers' => 'lib/eventhandlers.php',
]);

use Bitrix\Main\EventManager;

EventManager::getInstance()->addEventHandler(
    'crm',
    'onEntityDetailsTabsInitialized',
    ['\MyCompany\MyCustomModule\EventHandlers', 'onEntityDetailsTabsInitialized']
);
