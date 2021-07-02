<?php

class PhpDirectory {

    public static function getDirectory($patch) {
        $result = array();
        $handle = @opendir($patch);
        while ($handle && ($file = readdir($handle))) {
            if (is_file($patch . "/" . $file)) {
                $result[] = $patch . "/" . $file;
            }
            if (is_dir($patch . "/" . $file) && ($file != ".") && ($file != "..")) {
                $result = array_merge($result, self::getDirectory($patch . "/" . $file));  // Обходим вложенный каталог
            }
        }
        if ($handle)
            closedir($handle);
        return $result;
    }

}
