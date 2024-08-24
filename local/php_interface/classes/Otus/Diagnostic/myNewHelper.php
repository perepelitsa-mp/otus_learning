<?php
namespace Otus\Diagnostic;

class myNewHelper {
    public static function writeToLog($data, $title = ''): bool
    {
        $logFileName = $_SERVER["DOCUMENT_ROOT"] . '/logs/' . date("Y-m-d") . '_custom.log';
        if (!file_exists(dirname($logFileName)) || !is_writable(dirname($logFileName))) {
            error_log("Директория для логов недоступна для записи: " . dirname($logFileName), 0);
            return false;
        }
        $log = "\n------------------------\n";
        $log .= date("Y.m.d G:i:s") . " OTUS\n";
        $log .= (strlen($title) > 0 ? $title : 'DEBUG') . " OTUS\n";
        $log .= print_r($data, 1) . " OTUS\n";
        $log .= "\n------------------------\n";
        file_put_contents($logFileName, $log, FILE_APPEND);
        return true;
    }
}
