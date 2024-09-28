<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use MyCompany\MyCustomModule\MyCustomTable;
use Bitrix\Crm\CompanyTable;

class MyCustomListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('mycompany.mycustommodule') || !Loader::includeModule('crm')) {
            ShowError('Модули не загружены.');
            return;
        }
        $companyId = $this->arParams['ENTITY_ID'] ?? null;

        if (!$companyId) {
            ShowError("ID сущности CRM не найден.");
            return;
        }
        $this->prepareData($companyId);
        $this->includeComponentTemplate();
    }

    protected function prepareData($companyId)
    {
        $company = CompanyTable::getById($companyId)->fetch();
        if (!$company) {
            ShowError("Компания с таким ID не найдена.");
            return;
        }
        $customData = MyCustomTable::getList([
            'filter' => ['COMPANY.ID' => $companyId],
            'select' => ['ID', 'NAME', 'DATE_CREATE', 'DESCRIPTION', 'COMPANY_TITLE' => 'COMPANY.TITLE'],
            'order' => ['DATE_CREATE' => 'DESC'],  // Упорядочиваем по дате создания
        ])->fetchAll();
        $this->arResult['COMPANY'] = $company;
        $this->arResult['ITEMS'] = $customData;
    }
}
