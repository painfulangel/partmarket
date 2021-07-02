<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KatalogVavto
 *
 * @author Sergij
 */
class KatalogVavtoImportItemsModel extends CFormModel {

    public $fileImport = null;

    /**
     *
     * @var String  charset of file
     */
    public $fileCharset = 'UTF-8';

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('fileImport', 'file', 'allowEmpty' => false, 'types' => 'txt, csv', 'maxSize' => Yii::app()->controller->module->maxFileSize),
            array('fileCharset,file', 'required'),
        );
    }

    public function import(&$text) {
        $this->fileImport = CUploadedFile::getInstance($this, 'fileImport');

        if ($this->fileImport != NULL) {
            $filename = pathinfo($this->fileImport->getName());
            $extension = $filename['extension'];
            $filename = md5(time()) . '.' . $extension;
            $this->fileImport->saveAs(Yii::app()->controller->module->pathFiles . $filename);
            KatalogVavtoItems::model()->importTXT(Yii::app()->controller->module->pathFiles . $filename, $this->fileCharset, $text);
            unlink(Yii::app()->controller->module->pathFiles . $filename);
            return true;
        }
        return false;
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'fileImport' => 'Файл импорта',
            'fileCharset' => 'Кодировка',
        );
    }

}
