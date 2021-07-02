<?php

class ClearFiles extends CConsoleCommand {
    /*     * in cron add record
      02 01 * * *  php /fullpath_to_site/protected/yiic.php ClearFiles
     * 
     */

    public function GetContents($dir, $files = array()) {
        if (!($res = opendir($dir)))
            exit("Нет такой директории...");
        while (($file = readdir($res)) == TRUE)
            if ($file != "." && $file != "..") {
                if (is_dir("$dir/$file")) {
                    array_push($files, "$dir/$file");
//                    $files = $this->GetContents("$dir/$file", $files);
                } else
                    array_push($files, "$dir/$file");
            }
        closedir($res);
//        print_r($files);
        return $files;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function run($args) {
        set_time_limit(300);
        $path = realpath(Yii::app()->basePath . '/../upload_files/ftp');
        $files = $this->GetContents($path);
        foreach ($files as $file) {
            if (file_exists($file))
                if (time() - filemtime($file) > 14400)
//                    echo $file."\n";
                    is_dir($file) ? @$this->removeDirectory($file) : @unlink($file); // @unlink($file);
        }
    }

}
