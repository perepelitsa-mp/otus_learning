<?php

namespace UserTypes;

use Bitrix\Main\UserField\TypeBase;
use Bitrix\Main\Localization\Loc;
use CUserTypeManager;
use Bitrix\Main\Loader;

class BookingProceduresField extends TypeBase
{
    const USER_TYPE_ID = 'booking_procedures_field';

    public static function GetUserTypeDescription()
    {
        return [
            'USER_TYPE_ID' => self::USER_TYPE_ID,
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Запись на процедуры',
            'BASE_TYPE' => CUserTypeManager::BASE_TYPE_STRING,
            'EDIT_CALLBACK' => [__CLASS__, 'GetEditFormHTML'],
            'VIEW_CALLBACK' => [__CLASS__, 'GetAdminListViewHTML'],
        ];
    }

    public static function GetDBColumnType($arUserField)
    {
        return 'text';
    }

    public static function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        return static::renderField($arUserField, $arHtmlControl);
    }

    public static function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {
        return static::renderField($arUserField, $arHtmlControl);
    }

    public static function renderField($arUserField, $arHtmlControl)
    {
        $doctorId = $_REQUEST['ID'];
        $procedures = [];

        if (Loader::includeModule('iblock') && $doctorId)
        {
            // Получаем связанные процедуры с врачом
            $res = \CIBlockElement::GetProperty(29, $doctorId, array(), array("CODE" => "PROCEDURES"));
            while ($ob = $res->GetNext())
            {
                $procedures[] = $ob['VALUE'];
            }

            // Формируем HTML для вывода процедур
            $html = '<ul>';
            foreach ($procedures as $procedureId)
            {
                $procedure = \CIBlockElement::GetByID($procedureId)->GetNext();
                $html .= '<li><a href="#" class="procedure-link" data-procedure-id="' . $procedureId . '">' . htmlspecialchars($procedure['NAME']) . '</a></li>';
            }
            $html .= '</ul>';

            return $html;
        }

        return '';
    }
}
