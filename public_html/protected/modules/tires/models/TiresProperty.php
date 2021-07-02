<?php
class TiresProperty extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'tires_property';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('name', 'required'),
				array('closed', 'numerical', 'integerOnly' => true),
				array('name_eng', 'safe'),
				array('id, name, name_eng, closed', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => Yii::t('tires', 'Tires property name'),
			'closed' => Yii::t('tires', 'Tires property closed'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
	
	public function relations()
	{
		return array(
			'propertyValues' => array(self::HAS_MANY, 'TiresPropertyValues', 'id_property')
		);
	}
}