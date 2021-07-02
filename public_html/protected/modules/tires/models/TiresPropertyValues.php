<?php
class TiresPropertyValues extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'tires_property_values';
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'value' => Yii::t('tires', 'Tires property value'),
			'popular' => Yii::t('tires', 'Tires property popular'),
		);
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('value', 'required'),
				array('id_property, popular, order', 'numerical', 'integerOnly' => true),
				array('value', 'length', 'max' => 255),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, id_property, value, popular, order', 'safe', 'on' => 'search'),
		);
	}
	
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'property' => array(self::HAS_ONE, 'TiresProperty', array('id' => 'id_property')),
		);
	}
	
	public function afterSave() {
		if ($this->isNewRecord) {
			self::model()->updateByPk($this->primaryKey, array('order'=>$this->primaryKey));
		}
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id_property', $this->id_property);
		$criteria->compare('popular', $this->popular);
		$criteria->order = 'popular DESC, `order` ASC, id ASC';
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 200,
			),
		));
	}
	
	public static function selectList($id_property = 1) {
		$array = array();
		
		$items = self::model()->findAll(array('condition' => 'id_property = '.intval($id_property), 'order' => 'value ASC'));
		
		$count = count($items);
		for ($i = 0; $i < $count; $i ++) {
			$array[$items[$i]->primaryKey] = $items[$i]->value;
		}
		
		return $array;
	}
	
	public function getPopularDataProvider($id_property = 1) {
		$criteria = new CDbCriteria;
		
		$criteria->compare('id_property', $id_property);
		$criteria->order = 'popular DESC, IF(value_int_min IS NOT NULL, value_int_min, `order`) ASC';
		
		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
				'pageSize' => 200,
			),
		));
	}
	
	public function isUsed() {
		return Tires::model()->countByAttributes(array($this->property->name_eng => $this->primaryKey)) > 0 ? false : true;
	}
}