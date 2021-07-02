<?php
class UniversalProductImport extends CFormModel {
    public $fileImport = null;
	public $razdelId;
	
    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('fileImport', 'file', 'allowEmpty' => false, 'types' => 'txt, csv', 'maxSize' => Yii::app()->controller->module->maxFileSize),
        );
    }

    public function import() {
        $this->fileImport = CUploadedFile::getInstance($this, 'fileImport');

        if ($this->fileImport != NULL) {
            $filename = pathinfo($this->fileImport->getName());
            $extension = $filename['extension'];
            $filename = md5(time()) . '.' . $extension;
            $this->fileImport->saveAs(Yii::app()->controller->module->pathFiles . $filename);
            UniversalRazdel::model()->import(Yii::app()->controller->module->pathFiles . $filename, $this->razdelId);
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
            'fileImport' => Yii::t('universal', 'Import File'),
        	'razdelId' => Yii::t('universal', 'Section ID'),
        );
    }
}