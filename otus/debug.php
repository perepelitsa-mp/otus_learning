<?php
$logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs/test.log';
$currentDateTime = date('Y-m-d H:i:s');
if (!file_exists($logFile)) {
    if (touch($logFile)) {
        echo "Файл test.log успешно создан.";
    } else {
        echo "Не удалось создать файл test.log.";
    }
}
if (is_writable($logFile)) {
    file_put_contents($logFile, "Current Date and Time: " . $currentDateTime . PHP_EOL, FILE_APPEND);
    echo "Дата и время успешно записаны в файл test.log.";
} else {
    echo "Файл test.log недоступен для записи.";
}
?>
