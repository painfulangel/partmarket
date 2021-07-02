<?php

class BackgroundProcess {

    /**
     * 
     * @param type $call
     * @param string $file
     * @param type $errors
     */
    public static function launchBackgroundProcessStart($call, $file = false, $errors = false) {
        if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
            $handle = popen('start /MIN ' . $call, "r");
            if ($handle === false)
                trigger_error("Can't start process $call", E_USER_ERROR);
            else
                pclose($handle);
        }
        else {
            $add = "";
            if ($file === false) {
                $file = '/dev/null';
            }
            if ($errors !== false) {
                $add = " 2> " . $errors;
            }
            exec($cmd = ($call . ' > ' . $file . $add . ' &'));
        }
    }

}
