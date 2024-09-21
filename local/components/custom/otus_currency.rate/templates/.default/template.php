<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
$templateFolder = $this->GetFolder();
$APPLICATION->SetAdditionalCSS($templateFolder . "/styles.css");
?>

<div class="currency-container">
    <h2>Курсы валют</h2>
    <form method="post" class="currency-form" id="currency-form">
        <?= bitrix_sessid_post() ?>
        <label for="currency-select">Выберите валюту:</label>
        <select name="CURRENCY" id="currency-select">
            <?php foreach ($arResult['CURRENCIES'] as $currencyCode => $currencyData): ?>
                <option value="<?= htmlspecialcharsbx($currencyCode) ?>" <?= ($currencyCode == $arResult['SELECTED_CURRENCY']) ? 'selected' : '' ?>>
                    <?= htmlspecialcharsbx("[$currencyCode] " . $currencyData['FULL_NAME']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="currency-result" id="currency-result">
        <?php if (!empty($arResult['SELECTED_CURRENCY'])): ?>
            <p>Курс валюты <strong><?= htmlspecialcharsbx($arResult['SELECTED_CURRENCY']) ?></strong>: <?= htmlspecialcharsbx($arResult['SELECTED_RATE']) ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currencies = <?= CUtil::PhpToJSObject($arResult['CURRENCIES']) ?>;
        var currencySelect = document.getElementById('currency-select');
        var currencyResult = document.getElementById('currency-result');

        function updateCurrencyRate() {
            var selectedCurrency = currencySelect.value;
            var rate = currencies[selectedCurrency]['AMOUNT'];
            var currencyCode = selectedCurrency;
            currencyResult.innerHTML = '<p>Курс валюты <strong>' + currencyCode + '</strong>: ' + rate + '</p>';
        }

        updateCurrencyRate();
        currencySelect.addEventListener('change', function() {
            updateCurrencyRate();
        });
    });
</script>
