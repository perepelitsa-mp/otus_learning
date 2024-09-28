<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Grid</title>
    <style>
        .custom-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 3fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .custom-grid-header, .custom-grid-row {
            display: contents;
        }

        .custom-grid-header > div, .custom-grid-row > div {
            padding: 10px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
        }

        .custom-grid-header {
            font-weight: bold;
            background-color: #e5e5e5;
        }

        .no-data {
            grid-column: span 5;
            text-align: center;
            padding: 20px;
            background-color: #fefefe;
            border: 1px solid #ddd;
        }

    </style>
</head>
<body>

<div class="custom-grid">
    <div class="custom-grid-header">
        <div>ID</div>
        <div>Название</div>
        <div>Дата создания</div>
        <div>Описание</div>
    </div>

    <?php if (!empty($arResult['ITEMS'])): ?>
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <div class="custom-grid-row">
                <div><?= htmlspecialchars($item['ID']) ?></div>
                <div><?= htmlspecialchars($item['NAME']) ?></div>
                <div><?= $item['DATE_CREATE']->format('d.m.Y H:i:s') ?></div>
                <div><?= htmlspecialchars($item['DESCRIPTION']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-data">Нет данных для отображения</div>
    <?php endif; ?>
</div>

</body>
</html>
