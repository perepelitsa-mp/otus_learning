<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle('Список автомобилей');

use Bitrix\Main\Type;
use Models\CarsSQLTable as CarsTableSQL;
use Bitrix\Iblock\ElementTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/local/app/functions/get_brand_name.php");

$collection = CarsTableSQL::getList([
    'select' => [
        '*',
        'BRANDS',
        'MODEL',
        'CAR',
        'GAI'
    ],
])->fetchCollection();

?>
    <style>
        .table-container {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            text-align: center;
        }

        .table-header, .table-cell {
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
        }

        .table-header {
            font-weight: bold;
            background-color: #e0e0e0;
        }
    </style>
    <div class="table-container">
        <div class="table-header">ID</div>
        <div class="table-header">Название</div>
        <div class="table-header">Год выпуска</div>
        <div class="table-header">VIN</div>
        <div class="table-header">Бренд</div>
        <div class="table-header">Модель</div>
        <div class="table-header">Цвет</div>
        <div class="table-header">Данные ГАИ</div>
        <div class="table-header">БУ</div>
        <?php foreach ($collection as $car): ?>
            <div class="table-cell"><?= $car->getId(); ?></div>
            <div class="table-cell"><?= $car->getName(); ?></div>
            <div class="table-cell"><?= $car->getCar()->getYear() ?></div>
            <div class="table-cell"><?= $car->getCarVin(); ?></div>
            <div class="table-cell"><?= $car->getBrands()->getName();; ?></div>
            <div class="table-cell"><?= $car->getModel()->getName() ?></div>
            <div class="table-cell"><?= $car->getCar()->getColor(); ?></div>
            <div class="table-cell"><?= $car->getGai()->getName(); ?></div>
            <div class="table-cell"><?= $car->getCar()->getSh(); ?></div>
        <?php endforeach; ?>
    </div>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
