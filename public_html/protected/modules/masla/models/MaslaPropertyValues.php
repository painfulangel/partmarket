<?php
class MaslaPropertyValues extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'masla_chars_values';
	}

	public function attributeLabels() {
		$additional = array();
	
		return array(
			'id' => 'ID',
			'value' => Yii::t('masla', 'Value'),
			'value_number' => Yii::t('masla', 'Value'),
			'popular' => Yii::t('masla', 'Popular'),
		);
	}
	
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'property' => array(self::HAS_ONE, 'MaslaProperty', array('id' => 'id_chars')),
		);
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_chars', 'required'),
			array('id_chars, popular', 'numerical', 'integerOnly' => true),
			array('value', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_chars, value, value_number, popular', 'safe', 'on' => 'search'),
		);
	}
	
	public static function selectList($id) {
		$list = array();
		
		$items = self::model()->findAll(array('condition' => 'id_chars = '.intval($id), 'order' => 'value ASC'));
		$count = count($items);
		for ($i = 0; $i < $count; $i ++) {
			$list[$items[$i]->primaryKey] = $items[$i]->value;
		}
		
		return $list;
	}

	public function getPopularDataProvider($id_property = 1) {
		$criteria = new CDbCriteria;
	
		$criteria->compare('id_chars', $id_property);
		$criteria->order = 'popular DESC, IF(value_number IS NOT NULL, value_number, value) ASC';
	
		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
				'pageSize' => 500,
			),
		));
	}

	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id_chars', $this->id_chars);
		$criteria->compare('popular', $this->popular);
		
		if ($this->id_chars) {
			$p = MaslaProperty::model()->findByPk($this->id_chars);
			if (is_object($p)) {
				if ($p->number)
					$criteria->order = 'popular DESC, value_number ASC';
				else
					$criteria->order = 'popular DESC, value ASC';
			}
		} else {
			$criteria->order = 'popular DESC, IF(value_number IS NOT NULL, value_number, value) ASC';
		}
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 50,
			),
		));
	}
}