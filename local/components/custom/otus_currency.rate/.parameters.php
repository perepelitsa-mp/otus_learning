<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Currency;

if (!Loader::includeModule('currency'))
    return;

$arCurrencies = array();

$currencyIterator = Currency\CurrencyTable::getList(array(
    'select' => array('CURRENCY'),
    'order' => array('SORT' => 'ASC')
));
while ($currency = $currencyIterator->fetch()) {
    $currencyCode = $currency['CURRENCY'];
    $currencyLangIterator = Currency\CurrencyLangTable::getList(array(
        'select' => array('FULL_NAME'),
        'filter' => array('=CURRENCY' => $currencyCode, '=LID' => LANGUAGE_ID)
    ));
    if ($currencyLang = $currencyLangIterator->fetch()) {
        $fullName = $currencyLang['FULL_NAME'];
    } else {
        $fullName = $currencyCode;
    }

    $arCurrencies[$currencyCode] = '[' . $currencyCode . '] ' . $fullName;
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "DEFAULT_CURRENCY" => array(
            "PARENT" => "BASE",
            "NAME" => "Валюта по умолчанию",
            "TYPE" => "LIST",
            "VALUES" => $arCurrencies,
            "DEFAULT" => 'USD',
            "ADDITIONAL_VALUES" => "N",
            "REFRESH" => "N",
        ),
    )
);
