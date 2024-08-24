<?php
$logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs/dateAndTime.log';
$currentDateTime = date('Y-m-d H:i:s');
if (!file_exists($logFile)) {
    touch($logFile);
}
if (is_writable($logFile)) {
    file_put_contents($logFile, "Текущее время и дата: " . $currentDateTime . PHP_EOL, FILE_APPEND);
}
?>
