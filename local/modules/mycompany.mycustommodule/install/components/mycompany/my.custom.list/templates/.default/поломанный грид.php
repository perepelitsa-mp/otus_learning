<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;
Extension::load("ui.grid");

$gridId = 'my_custom_list';

$columns = [
    ['id' => 'ID', 'name' => 'ID', 'default' => true],
    ['id' => 'NAME', 'name' => 'Название', 'default' => true],
    ['id' => 'DATE_CREATE', 'name' => 'Дата создания', 'default' => true],
    ['id' => 'DESCRIPTION', 'name' => 'Описание', 'default' => true],
];

$rows = [];
if (!empty($arResult['ITEMS'])) {
    foreach ($arResult['ITEMS'] as $item) {
        if ($item['DATE_CREATE'] instanceof \Bitrix\Main\Type\DateTime) {
            $item['DATE_CREATE'] = $item['DATE_CREATE']->toString();
        }
        $rows[] = ['data' => $item];
    }
}

if (!empty($rows)) {
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        [
            'GRID_ID' => $gridId,
            'COLUMNS' => $columns,
            'ROWS' => $rows,
            'SHOW_ROW_CHECKBOXES' => false,
            'AJAX_MODE' => 'N',
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_TOTAL_COUNTER' => true,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => false,
            'ALLOW_COLUMNS_SORT' => true,
            'ALLOW_COLUMNS_RESIZE' => true,
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => true,
            'ALLOW_PIN_HEADER' => true,
            'AJAX_OPTION_HISTORY' => 'N',
            'CACHE_TYPE' => 'N'
        ]
    );
} else {
    echo '<p>Нет данных для отображения</p>';
}
