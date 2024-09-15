<?php

namespace Models;


use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DateField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Validator\Base,
    Bitrix\Main\ORM\Fields\Validators\RegExpValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\Entity\Query\Join;

use Models\Lists\CarGaiPropertyValueTable;
use Models\Lists\CarsBrandsValuesTable as CarsBrandsTable;
use Models\Lists\CarModelsValuesTable as CarModelsValuesTable;
use Models\Lists\CarsListPropertyValuesTable;

/**
 * Class CarsSQLTable
 *
 * @package Models
 */
class CarsSQLTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'cars';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            'id' => (new IntegerField('id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'name' => (new StringField('name',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('_ENTITY_NAME_FIELD')),
            (new IntegerField('car_brand_id')),
            (new Reference(
                'BRANDS',
                CarsBrandsTable::class,
                Join::on('this.car_brand_id', 'ref.BRAND_ID')
            ))
                ->configureJoinType('inner'),
            'car_model_id' => (new IntegerField('car_model_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_CAR_MODEL_ID_FIELD')),
            (new Reference(
                'MODEL',
                CarModelsValuesTable::class,
                Join::on('this.car_model_id', 'ref.CAR_MODEL_ID')
            ))->configureJoinType('inner'),
            'car_year' => (new DateField('car_year',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_CAR_YEAR_FIELD')),
            (new Reference(
                'CAR',
                CarsListPropertyValuesTable::class,
                Join::on('this.id', 'ref.CAR_ID')
            ))->configureJoinType('inner'),
            'car_vin' => (new StringField('car_vin',
                [
                    'validation' => [__CLASS__, 'validateIsbn']
                ]
            ))
                ->configureTitle(Loc::getMessage('_ENTITY_CAR_VIN_FIELD')),

            'car_gai_id' => (new IntegerField('car_gai_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_GAI_ID_FIELD')),
            (new Reference(
                'GAI',
                CarGaiPropertyValueTable::class,
                Join::on('this.car_gai_id', 'ref.ID')
            ))->configureJoinType('inner'),

        ];
    }

    /**
     * Returns validators for name field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(3, 50),
        ];
    }

    /**
     * Returns validators for ISBN field.
     *
     * @return array
     */
    public static function validateIsbn()
    {
        return
            array(function($value) {
                $clean = str_replace('-', '', $value);
                if (preg_match('/[\d-]{13,}/', $clean))
                {
                    return true;
                }
                else
                {
                    return 'Код ISBN должен содержать 13 цифр.';
                }
            });
    }
}
