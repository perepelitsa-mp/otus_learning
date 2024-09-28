<?php
if (!check_bitrix_sessid()) return;

echo CAdminMessage::ShowNote("Модуль успешно удалён.");
?>

<form action="<?= $APPLICATION->GetCurPage() ?>" method="GET">
    <input type="hidden" name="lang" value="<?= LANG ?>" />
    <input type="submit" value="Вернуться" />
</form>
