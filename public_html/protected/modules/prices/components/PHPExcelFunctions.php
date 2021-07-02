<?php

class PHPExcelFunctions {

    /**
     * 
     * @param type $inputFileType
     * @param type $startRow
     * @param type $chunkSize
     * @param type $dir
     * @param type $filenameF
     * @return type
     */
    public static function getStartData($inputFileType,$startRow,$chunkSize,$dir,$filename) {
        $chunkFilter = new PHPExcelBigFileFilter;
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//        $workSheetInfo = $objReader->listWorksheetInfo($dir . $filename);
        $chunkFilter->setRows($startRow, $chunkSize);
//        $objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($dir . $filename);
        $sheet = $objPHPExcel->getSheet(0);

//        $highestRow = $sheet->getHighestRow();
//        $highestColumn = $sheet->getHighestColumn();

        return array($sheet->getHighestRow(), $sheet->getHighestColumn());
    }

}
