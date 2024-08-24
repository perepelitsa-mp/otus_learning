<?php

namespace Otus\Diagnostic;
class Helper {
    public static function writeToLog($data, $title = ''): bool
    {
        if (!defined('DEBUG_FILE_NAME') || !DEBUG_FILE_NAME) {
            error_log("DEBUG_FILE_NAME не определено или пусто", 0);
            return false;
        }

        $log = "\n------------------------\n";
        $log .= date("Y.m.d G:i:s")."\n";
        $log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
        $log .= print_r($data, 1);
        $log .= "\n------------------------\n";

        file_put_contents(DEBUG_FILE_NAME, $log, FILE_APPEND);
        return true;
    }
};
