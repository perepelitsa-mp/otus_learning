<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight("mycompany.mycustommodule") >= "R") {
    $aMenu = [
        "parent_menu" => "global_menu_services",
        "section" => "my_custom_module",
        "sort" => 200,
        "text" => Loc::getMessage("MY_CUSTOM_MODULE_MENU_TITLE"),
        "title" => Loc::getMessage("MY_CUSTOM_MODULE_MENU_TITLE"),
        "url" => "my_custom_module.php",
        "items_id" => "menu_my_custom_module",
        "items" => [
            [
                "text" => Loc::getMessage("MY_CUSTOM_MODULE_MENU_LIST"),
                "url" => "my_custom_module_list.php",
                "more_url" => ["my_custom_module_list.php"],
                "title" => Loc::getMessage("MY_CUSTOM_MODULE_MENU_LIST"),
            ],
        ],
    ];
    return $aMenu;
}
return false;
