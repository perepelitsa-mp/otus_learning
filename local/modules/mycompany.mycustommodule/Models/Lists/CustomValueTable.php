<?php

namespace MyCompany\CustomModule;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;

class MyCustomTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_custom_table';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),

            new IntegerField('CRM_ID', [
                'required' => true,
            ]),
            new StringField('NAME', [
                'required' => true,
            ]),
            new DatetimeField('DATE_CREATE', [
                'required' => true,
            ]),
            new StringField('DESCRIPTION', [
                'required' => false,
            ]),
            // виртуальное поле CONTACT которое получает запись из таблицы контактов
            // где ID равен значению contact_id таблицы hospital_clients

        ];
    }
}
