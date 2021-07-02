<?php
class UniversalProductBase extends UniversalProduct {
	private static $additionalRules = array();
	private static $tableName = 'universal_products';
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return self::$tableName;
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array_merge(array(
			array('id_razdel, name, article', 'required'),
			array('id_razdel', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
			array('meta_keywords, meta_description, meta_title, anons, content, analogs', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_razdel, name, article, meta_title, meta_description, meta_keywords, anons, content, analogs, active_state', 'safe', 'on' => 'search'),
		), self::$additionalRules);
	}
	
	public static function modelBase($tableName, $additionalRules = array()) {
		self::$tableName = $tableName;
		self::$additionalRules = $additionalRules;
		
		return self::model();
	}
}