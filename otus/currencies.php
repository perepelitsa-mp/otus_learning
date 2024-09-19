<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Курсы валют");

$APPLICATION->IncludeComponent(
    "custom:otus_currency.rate",
    "",
    array(
        "DEFAULT_CURRENCY" => "",
        "CACHE_TYPE" => "N",
    )
);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
