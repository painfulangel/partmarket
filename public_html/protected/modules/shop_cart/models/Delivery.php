<?php
class Delivery extends CMyActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CMyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Items the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'delivery';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('price, transport, active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, price, transport, active', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => Yii::t('delivery', 'Name'),
			'price' => Yii::t('delivery', 'Price'),
		    'transport' => Yii::t('delivery', 'Transport company'),
			'active' => Yii::t('delivery', 'Active'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
		
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('transport', $this->transport);
		$criteria->compare('active', $this->active);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
}