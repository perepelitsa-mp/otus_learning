<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
$templateFolder = $this->GetFolder();
$APPLICATION->SetAdditionalCSS($templateFolder . "/styles.css");
?>

<div class="currency-container">
    <div class="currency-result" id="currency-result">
        <?php if (!empty($arResult['SELECTED_CURRENCY'])): ?>
            <p>Курс валюты <strong><?= htmlspecialcharsbx($arResult['SELECTED_CURRENCY']) ?></strong>: <?= htmlspecialcharsbx($arResult['SELECTED_RATE']) ?></p>
        <?php endif; ?>
    </div>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currencies = <?= CUtil::PhpToJSObject($arResult['CURRENCIES']) ?>
        let currencySelect = document.getElementById('currency-select')
        let currencyResult = document.getElementById('currency-result')
        function updateCurrencyRate() {
            let selectedCurrency = currencySelect.value
            let rate = currencies[selectedCurrency]['AMOUNT']
            let currencyCode = selectedCurrency

            currencyResult.innerHTML = '<p>Курс валюты <strong>' + currencyCode + '</strong>: ' + rate + '</p>'
        }
        updateCurrencyRate()
        currencySelect.addEventListener('change', function() {
            updateCurrencyRate()
        })
    })
</script>
