<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Loader;

class ListsElementEditComponent extends CBitrixComponent implements Controllerable
{
    public function executeComponent()
    {
        if ($this->request->isPost() && check_bitrix_sessid())
        {
            $formData = $this->request->getPost('formData');


            // Обработка и сохранение данных
            $iblockId = $this->arParams['IBLOCK_ID'];
            $elementId = $this->arParams['ELEMENT_ID'] ?: 0;

            $el = new CIBlockElement;

            $arFields = [
                "IBLOCK_ID" => $iblockId,
                "PROPERTY_VALUES" => [],
            ];

            foreach ($formData as $fieldId => $value)
            {
                if ($fieldId === 'NAME')
                {
                    $arFields['NAME'] = $value;
                }
                elseif (strpos($fieldId, 'PROPERTY_') === 0)
                {
                    $propertyId = str_replace('PROPERTY_', '', $fieldId);
                    $arFields["PROPERTY_VALUES"][$propertyId] = $value;
                }
                else
                {
                    $arFields[$fieldId] = $value;
                }
            }

            if ($elementId > 0)
            {
                $result = $el->Update($elementId, $arFields);
            }
            else
            {
                $result = $el->Add($arFields);
            }

            if (!$result)
            {
                $this->arResult['ERROR'] = $el->LAST_ERROR;
            }
            else
            {
                $this->arResult['SUCCESS'] = true;
            }

            // Возвращаем ответ в формате JSON
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            header('Content-Type: application/json');

            if ($this->arResult['SUCCESS'])
            {
                echo json_encode(['status' => 'success']);
            }
            else
            {
                echo json_encode(['status' => 'error', 'message' => $this->arResult['ERROR']]);
            }

            die();
        }

        // Подготовка данных для шаблона
        $this->arResult['FIELDS'] = $this->prepareFields();
        $this->arResult['FORM_DATA'] = $this->prepareFormData();

        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [
            'save' => [
                'prefilters' => [
                    new \Bitrix\Main\Engine\ActionFilter\HttpMethod(
                        [\Bitrix\Main\Engine\ActionFilter\HttpMethod::METHOD_POST]
                    ),
                    new \Bitrix\Main\Engine\ActionFilter\Csrf(),
                ],
                'bindings' => [
                    'iblockId' => 'data',
                    'formData' => 'data',
                ],
            ],
        ];
    }

    public function saveAction($iblockId, $formData)
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            throw new \Bitrix\Main\SystemException('Пользователь не авторизован');
        }

        $iblockId = intval($iblockId);
        if ($iblockId <= 0) {
            return ['status' => 'error', 'message' => 'Неверный код информационного блока'];
        }

        $elementId = isset($formData['ELEMENT_ID']) ? intval($formData['ELEMENT_ID']) : 0;

        $el = new \CIBlockElement;

        $arFields = [
            "IBLOCK_ID" => $iblockId,
            "PROPERTY_VALUES" => [],
        ];

        foreach ($formData as $fieldId => $value) {
            if ($fieldId === 'NAME') {
                $arFields['NAME'] = $value;
            } elseif (strpos($fieldId, 'PROPERTY_') === 0) {
                $propertyId = str_replace('PROPERTY_', '', $fieldId);
                $arFields["PROPERTY_VALUES"][$propertyId] = $value;
            }
        }

        if ($elementId > 0) {
            $result = $el->Update($elementId, $arFields);
        } else {
            $elementId = $el->Add($arFields);
            $result = ($elementId > 0);
        }

        if (!$result) {
            $errorMessage = $el->LAST_ERROR;
            return ['status' => 'error', 'message' => $errorMessage];
        } else {
            return ['status' => 'success', 'elementId' => $elementId];
        }
    }


    private function prepareFields()
    {
        $fields = [];

        // Добавляем системное поле "Название"
        $fields[] = [
            'FIELD_ID' => 'NAME',
            'NAME' => 'Название',
            'TYPE' => 'S',
            'REQUIRED' => 'Y',
        ];

        // Получение свойств инфоблока
        $properties = CIBlockProperty::GetList([], ['IBLOCK_ID' => $this->arParams['IBLOCK_ID']]);
        while ($prop = $properties->Fetch())
        {
            $items = [];

            if ($prop['PROPERTY_TYPE'] == 'L')
            {
                // Получаем значения списка
                $propertyEnums = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['PROPERTY_ID' => $prop['ID']]);
                while ($enumFields = $propertyEnums->GetNext())
                {
                    $items[] = [
                        'label' => $enumFields['VALUE'],
                        'value' => $enumFields['ID']
                    ];
                }
            }

            $fields[] = [
                'FIELD_ID' => 'PROPERTY_' . $prop['ID'],
                'NAME' => $prop['NAME'],
                'TYPE' => $prop['PROPERTY_TYPE'],
                'REQUIRED' => $prop['IS_REQUIRED'],
                'ITEMS' => $items,
            ];
        }

        return $fields;
    }


    private function prepareFormData()
    {
        $elementId = $this->arParams['ELEMENT_ID'];
        $formData = [];

        if ($elementId > 0)
        {
            $res = CIBlockElement::GetList([], ['ID' => $elementId], false, false, ['ID', 'NAME']);
            if ($element = $res->Fetch())
            {
                $formData['NAME'] = $element['NAME'];

                // Получение значений свойств
                $props = CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $elementId, [], ['ACTIVE' => 'Y']);
                while ($prop = $props->Fetch())
                {
                    $fieldId = 'PROPERTY_' . $prop['ID'];
                    $value = $prop['VALUE'];

                    if ($prop['PROPERTY_TYPE'] == 'L')
                    {
                        // Для свойств типа "список" используем VALUE_ENUM_ID
                        $value = $prop['VALUE_ENUM_ID'];
                    }

                    if ($prop['MULTIPLE'] == 'Y')
                    {
                        if (!isset($formData[$fieldId]))
                        {
                            $formData[$fieldId] = [];
                        }
                        $formData[$fieldId][] = $value;
                    }
                    else
                    {
                        $formData[$fieldId] = $value;
                    }
                }
            }
        }

        return $formData;
    }

}
?>
