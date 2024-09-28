<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class mycompany_mycustommodule extends CModule
{
    public $MODULE_ID = 'mycompany.mycustommodule';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('MY_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MY_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('MY_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('MY_PARTNER_URI');
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '20.00.00');
    }

    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallDB();
            $this->InstallFiles();
            $this->InstallEvents();

            $APPLICATION->IncludeAdminFile("Установка модуля", __DIR__ . "/step.php");
        } else {
            $APPLICATION->ThrowException(Loc::getMessage('MY_MODULE_INSTALL_ERROR_VERSION'));
        }
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallFiles($arParams = array())
    {
        $component_path = $this->GetPath() . '/install/components';

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($component_path)) {
            if (!CopyDirFiles($component_path, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components', true, true)) {
                throw new \Exception("Ошибка копирования компонентов");
            }
        } else {
            throw new \Bitrix\Main\IO\InvalidPathException($component_path);
        }
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/mycompany/");
    }

    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        $connection = Application::getConnection();

        if (!$connection->isTableExists('my_custom_table')) {
            $connection->queryExecute('
            CREATE TABLE IF NOT EXISTS `my_custom_table` (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `CRM_ID` int(11) NOT NULL,
                `NAME` varchar(255) NOT NULL,
                `DATE_CREATE` datetime NOT NULL,
                `DESCRIPTION` text,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

            // Добавление данных в таблицу
            $connection->queryExecute('
            INSERT INTO my_custom_table (CRM_ID, NAME, DATE_CREATE, DESCRIPTION) VALUES 
            (1, "Name_956", "2024-05-29 05:05:28", "Description_1749"),
            (1, "Name_374", "2024-02-17 14:27:41", "Description_4012"),
            (1, "Name_594", "2024-06-12 08:56:22", "Description_6721"),
            (2, "Name_826", "2024-04-22 12:38:44", "Description_7251"),
            (2, "Name_237", "2024-05-18 09:13:12", "Description_8312"),
            (2, "Name_485", "2024-01-13 11:07:59", "Description_4917"),
            (2, "Name_123", "2024-03-11 18:24:08", "Description_2674"),
            (3, "Name_756", "2024-02-02 07:32:18", "Description_8362"),
            (3, "Name_893", "2024-04-14 05:41:09", "Description_9056"),
            (3, "Name_125", "2024-05-27 14:13:34", "Description_1274"),
            (4, "Name_582", "2024-06-15 10:07:52", "Description_4728"),
            (4, "Name_937", "2024-01-25 13:19:22", "Description_9245"),
            (4, "Name_347", "2024-03-22 09:59:17", "Description_3654"),
            (5, "Name_829", "2024-05-05 08:49:06", "Description_8190"),
            (5, "Name_623", "2024-02-18 04:42:11", "Description_2345"),
            (5, "Name_467", "2024-04-13 16:18:49", "Description_8917"),
            (5, "Name_790", "2024-03-15 11:06:38", "Description_1543"),
            (6, "Name_104", "2024-02-11 07:33:28", "Description_5169"),
            (6, "Name_594", "2024-01-28 05:48:59", "Description_2514"),
            (6, "Name_873", "2024-04-09 13:37:24", "Description_9037"),
            (7, "Name_482", "2024-05-18 17:14:52", "Description_7642"),
            (7, "Name_632", "2024-03-10 07:31:25", "Description_3872"),
            (7, "Name_790", "2024-06-20 14:18:47", "Description_5769"),
            (8, "Name_234", "2024-01-11 09:49:18", "Description_6723"),
            (8, "Name_743", "2024-04-25 05:29:59", "Description_1934"),
            (8, "Name_385", "2024-02-16 18:21:34", "Description_3954"),
            (8, "Name_637", "2024-03-14 16:07:49", "Description_6152"),
            (9, "Name_392", "2024-05-07 13:48:31", "Description_8329"),
            (9, "Name_752", "2024-06-19 11:04:17", "Description_4016"),
            (9, "Name_284", "2024-03-21 15:14:38", "Description_2341"),
            (10, "Name_920", "2024-02-09 07:26:12", "Description_7138"),
            (10, "Name_482", "2024-06-18 09:38:53", "Description_1047"),
            (10, "Name_734", "2024-04-27 12:18:39", "Description_8532")
        ');
        }
    }


    public function UnInstallDB()
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists('my_custom_table')) {
            $connection->dropTable('my_custom_table');
        }
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\MyCompany\MyCustomModule\EventHandlers',
            'onEntityDetailsTabsInitialized'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\MyCompany\MyCustomModule\EventHandlers',
            'onEntityDetailsTabsInitialized'
        );
    }
}
