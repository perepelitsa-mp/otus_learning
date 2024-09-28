<?php
namespace MyCompany\MyCustomModule;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
Loader::includeModule('crm');
use Bitrix\Crm\CompanyTable;

class MyCustomTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'my_custom_table';
    }

    public static function getMap()
    {
        return [
            'ID' => new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            'CRM_ID' => new Entity\IntegerField('CRM_ID', [
                'required' => true,
            ]),
            'NAME' => new Entity\StringField('NAME', [
                'required' => true,
            ]),
            'DATE_CREATE' => new Entity\DatetimeField('DATE_CREATE', [
                'default_value' => new Type\DateTime(),
            ]),
            'DESCRIPTION' => new Entity\TextField('DESCRIPTION'),
            new ReferenceField(
                'COMPANY',
                CompanyTable::class,
                ['=this.CRM_ID' => 'ref.ID']
            ),
        ];
    }
}
