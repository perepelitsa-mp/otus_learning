<?php
namespace Otus\Diagnostic;

class OtusNewHelper {
    public static function writeToLog($exception, $logType)
    {
        if (!DEBUG_FILE_NAME)
            return false;
        $log = "\n------------------------\n";
        $log .= date("Y.m.d G:i:s") . "\n";
        $log .= (strlen($logType) > 0 ? $logType : 'DEBUG') . "\n";
        $log .= print_r($exception, 1);
        $log .= "\n------------------------\n";
        $logLines = explode("\n", $log);
        foreach ($logLines as &$line) {
            if (trim($line) !== '') {
                $line = 'OTUS - ' . $line;
            }
        }
        $logWithOtus = implode("\n", $logLines);
        file_put_contents(DEBUG_FILE_NAME, $logWithOtus, FILE_APPEND);
    }
}
