<?php

namespace MyCompany\MyCustomModule\Models;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class MyCustomTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'my_custom_table';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\IntegerField('CRM_ID', [
                'required' => true,
            ]),
            (new Reference('CRM_ID', \Bitrix\CRM\CompanyTable::class,
                Join::on('this.CRM_ID', 'ref.ID')))
                ->configureJoinType('inner'),
            new Entity\StringField('NAME', [
                'required' => true,
            ]),
            new Entity\DatetimeField('DATE_CREATE', [
                'default_value' => new Type\DateTime(),
            ]),
            new Entity\TextField('DESCRIPTION', [
                'required' => false,
            ]),
        ];
    }
}
