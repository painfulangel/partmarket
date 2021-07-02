<?php
class UniversalChars extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'universal_chars';
	}
	
	public static function getTypes() {
		return array(1 => Yii::t('universal', 'Type string'),
					 2 => Yii::t('universal', 'Type list'),
					 3 => Yii::t('universal', 'Type number'),
					 4 => Yii::t('universal', 'Type list number'),
					 5 => Yii::t('universal', 'Type range numbers'),
					 6 => Yii::t('universal', 'Type boolean'));
	}
	
	public static function getType($type) {
		$types = self::getTypes();
		
		return array_key_exists($type, $types) ? $types[$type] : '';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('name', 'required'),
				array('id_razdel, type, min, max, filter, filter_main, filter_view, order', 'numerical', 'integerOnly' => true),
				array('name', 'length', 'max' => 255),
				//array('meta_keywords, meta_description, meta_title', 'safe'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				//array('id, meta_title, meta_description, meta_keywords, name, active_state', 'safe', 'on' => 'search'),
		);
	}
	
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'values' => array(self::HAS_MANY, 'UniversalCharsListValues', 'id_chars'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'id_razdel' => Yii::t('universal', 'Section ID'),
			'name' => Yii::t('universal', 'Char name'),
			'type' => Yii::t('universal', 'Char type'),
			'min' => Yii::t('universal', 'Min value'),
			'max' => Yii::t('universal', 'Max value'),
			'filter' => Yii::t('universal', 'Filter contains field'),
			'filter_main' => Yii::t('universal', 'Main filter contains field'),
			'filter_view' => Yii::t('universal', 'View in filter'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('id_razdel', $this->id_razdel, true);
		
		$criteria->order = '`order` ASC';
	
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
	
	public function beforeSave() {
		if ($this->isNewRecord) {
			$item = Yii::app()->db->createCommand('SELECT MAX(`order`) AS o FROM '.$this->tableName().' WHERE id_razdel = '.$this->id_razdel)->queryRow();
			
			if (is_array($item) && array_key_exists('o', $item)) {
				$this->order = $item['o'] + 1;
			} else {
				$this->order = 1;
			}
		}
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		parent::afterSave();
		
		$request = Yii::app()->request;
		
		switch ($this->type) {
			case 2:
			case 4:
				$list = $request->getPost('list', array());
				$list_id = $request->getPost('list_id', array());
				
				$count = count($list);
				for ($i = 0; $i < $count; $i ++) {
					$item = trim($list[$i]);
					$id = intval($list_id[$i]);
					
					if ($item != '') {
						if ($id) {
							$value = UniversalCharsListValues::model()->findByPk($id);
						} else {
							$value = new UniversalCharsListValues();
						}
						
						$value->id_chars = $this->primaryKey;
						
						$attr = $this->type == 2 ? 'value_string' : 'value_number';
						$value->{$attr} = $item;
						
						$value->save();
					} else if ($id) {
						UniversalCharsListValues::model()->deleteByPk($id);
					}
				}
			break;
		}
		
		UniversalView::recreateView($this->id_razdel);
	}
	
	public function afterDelete() {
		UniversalProductChars::model()->deleteAll('id_chars = '.$this->primaryKey);
		
		UniversalView::recreateView($this->id_razdel);
		
		return parent::afterDelete();
	}
	
	public function getValues() {
		$values = array();
		
		foreach ($this->values as $v) {
			$attr = $this->type == 2 ? 'value_string' : 'value_number';
			
			$values[$v->primaryKey] = $v->{$attr};
		}
		
		return $values;
	}
	
	public function getValue($id_product) {
		$value = '';
		
		$item = UniversalProductChars::model()->findByAttributes(array('id_product' => $id_product, 'id_chars' => $this->primaryKey));
		
		if (is_object($item)) {
			if ($this->type == 1) {
				$value = $item->value_string;
			} else {
				$value = $item->value_number;
			}
		}
		
		return $value;
	}
	
	public function getListValue($id_list_value) {
		$item = UniversalCharsListValues::model()->findByPk($id_list_value);
		
		$value = '';
		
		if (is_object($item))
			if ($this->type == 2) {
				$value = $item->value_string;
			} else {
				$value = $item->value_number;
			}
		
		return $value;
	}
}