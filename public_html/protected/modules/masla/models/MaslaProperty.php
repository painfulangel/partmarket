<?php
class MaslaProperty extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'masla_chars';
	}
	
	public function attributeLabels() {
		$additional = array();
		
		return array(
			'id' => 'ID',
			'name' => Yii::t('masla', 'Name'),
			'filter' => Yii::t('masla', 'Show in filter'),
			'filter_closed' => Yii::t('masla', 'Oil property closed'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		
		$criteria->order = 'type ASC, id ASC';
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 60,
			),
		));
	}
}