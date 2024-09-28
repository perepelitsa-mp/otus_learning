<?php
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('NOT_CHECK_PERMISSIONS', 'Y');
define('PUBLIC_AJAX_MODE', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!check_bitrix_sessid()) {
    die('Invalid session');
}

$componentName = $_REQUEST['componentName'] ?? 'mycompany:my.custom.list';
$templateName = $_REQUEST['templateName'] ?? '';
$signedParameters = $_REQUEST['signedParameters'] ?? '';
$params = [];

if ($signedParameters !== '') {
    $params = \Bitrix\Main\Component\ParameterSigner::unsignParameters($componentName, $signedParameters);
}

$APPLICATION->IncludeComponent(
    $componentName,
    $templateName,
    $params
);
