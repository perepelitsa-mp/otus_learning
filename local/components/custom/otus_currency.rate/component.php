<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Currency;

$arResult['CURRENCIES'] = [];
$currencyIterator = Currency\CurrencyTable::getList([
    'select' => ['CURRENCY', 'AMOUNT'],
    'order' => ['SORT' => 'ASC']
]);
while ($currency = $currencyIterator->fetch()) {
    $currencyCode = $currency['CURRENCY'];
    $amount = $currency['AMOUNT'];

    $currencyLang = Currency\CurrencyLangTable::getRow([
        'select' => ['FULL_NAME'],
        'filter' => ['=CURRENCY' => $currencyCode, '=LID' => LANGUAGE_ID]
    ]);
    $fullName = $currencyLang ? $currencyLang['FULL_NAME'] : $currencyCode;

    $arResult['CURRENCIES'][$currencyCode] = [
        'FULL_NAME' => $fullName,
        'AMOUNT' => $amount,
    ];
}

$selectedCurrency = $arParams['DEFAULT_CURRENCY'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid()) {
    $currencyFromPost = htmlspecialcharsbx($_POST['CURRENCY']);
    if (!empty($currencyFromPost) && isset($arResult['CURRENCIES'][$currencyFromPost])) {
        $selectedCurrency = $currencyFromPost;
    }
}

$arResult['SELECTED_CURRENCY'] = $selectedCurrency;
$arResult['SELECTED_RATE'] = $arResult['CURRENCIES'][$selectedCurrency]['AMOUNT'];

$this->includeComponentTemplate();
