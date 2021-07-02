<?php
class UniversalCharsListValues extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'universal_chars_list_values';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_chars', 'required'),
			array('id_chars, value_number', 'numerical', 'integerOnly' => true),
			array('value_string', 'length', 'max' => 255),
			//array('meta_keywords, meta_description, meta_title', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, meta_title, meta_description, meta_keywords, name, active_state', 'safe', 'on' => 'search'),
		);
	}
}