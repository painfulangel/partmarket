<?php
class BrandsNew extends CMyActiveRecord {
	public $warehouse;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
        return 'brands_new';
	}

	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        return array(
            array('name', 'required'),
            array('price_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('id, name, price_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'price' => array(self::HAS_ONE, 'Prices', array('id' => 'price_id')),
		);
	}

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('brands', 'Name'),
            'price_id' => Yii::t('brands', 'Price'),
            'warehouse' => Yii::t('brands', 'Warehouse'),
        );
    }

    public function search() {
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('price_id', $this->price_id);
		
		$criteria->order = 'name ASC';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function selectPrices() {
		$data = array();

		$prices = Prices::model()->findAll(array('order' => 'name ASC'));

		$count = count($prices);
		for ($i = 0; $i < $count; $i ++) {
			$data[$prices[$i]->primaryKey] = $prices[$i]->name." (ID = ".$prices[$i]->primaryKey.")";
		}

		return $data;
	}
}
?>