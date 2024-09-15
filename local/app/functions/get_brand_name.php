
<?php

use Bitrix\Main\Loader;
Loader::includeModule('iblock');
function getBrandNameById($Id, $iblockCode, $property, $propertyId)
{
    $iblockID = \CIBlock::GetList(
        [],
        ['CODE' => $iblockCode],
        false
    )->Fetch();
    $res = \CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockID,
            $propertyId => $Id
        ],
        false,
        false,
    );
    if ($brand = $res->Fetch()) {
        return $brand[$property];
    }
    return 'NOT FOUND';
}
?>
