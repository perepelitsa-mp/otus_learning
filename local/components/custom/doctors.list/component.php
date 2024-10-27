<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

class DoctorsListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if(!Loader::includeModule("iblock"))
            return;

        $arSelect = [
            "ID",
            "NAME",
            "PROPERTY_PROCEDURES",
            "PROPERTY_BOOKING_PROCEDURES",
        ];
        $arFilter = [
            "IBLOCK_ID" => 29,
            "ACTIVE" => "Y"
        ];
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

        $this->arResult = [];
        while($ob = $res->GetNext())
        {
            $doctor = [
                "ID" => $ob["ID"],
                "NAME" => $ob["NAME"],
                "PROCEDURES" => [],
                "BOOKING_PROCEDURES_HTML" => $ob["~PROPERTY_BOOKING_PROCEDURES_VALUE"]["TEXT"], // Получаем HTML из пользовательского свойства
            ];

            // Обработка процедур врача
            if(!empty($ob["PROPERTY_PROCEDURES_VALUE"]))
            {
                $procedureIds = is_array($ob["PROPERTY_PROCEDURES_VALUE"]) ? $ob["PROPERTY_PROCEDURES_VALUE"] : [$ob["PROPERTY_PROCEDURES_VALUE"]];
                $procedureFilter = ["IBLOCK_ID" => 30, "ID" => $procedureIds];
                $procedureRes = CIBlockElement::GetList([], $procedureFilter, false, false, ["ID", "NAME"]);
                while($proc = $procedureRes->GetNext())
                {
                    $doctor["PROCEDURES"][] = [
                        "ID" => $proc["ID"],
                        "NAME" => $proc["NAME"],
                    ];
                }
            }

            $this->arResult[] = $doctor;
        }

        $this->includeComponentTemplate();
    }
}
?>
