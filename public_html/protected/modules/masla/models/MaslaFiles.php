<?php
class MaslaFiles extends CMyActiveRecord {
	public $filePath = 'masla/files/';
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'masla_files';
	}
	
	public function getAttachment() {
		$path = '/images/'.$this->filePath.$this->file;
	
		return $path;
	}
}