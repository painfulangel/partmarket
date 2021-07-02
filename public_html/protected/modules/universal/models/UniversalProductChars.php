<?php
class UniversalProductChars extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'universal_products_chars';
	}

	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_product, id_chars', 'required'),
			array('id_product, id_chars, value_number', 'numerical', 'integerOnly' => true),
			array('value_string', 'length', 'max' => 255),
		);
	}
}