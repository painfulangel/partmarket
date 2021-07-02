<?php
class BrandsIncorrect extends CMyActiveRecord {
	public $warehouse;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
        return 'brands_incorrect';
	}

	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        return array(
            array('name, date', 'required'),
            array('date', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('id, name, date', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
    	if ($this->isNewRecord) {
    		$this->date = time();
    	}

    	return parent::beforeSave();
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('brands', 'Name'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->order = 'name ASC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
?>