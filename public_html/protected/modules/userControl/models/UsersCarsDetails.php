<?php
class UsersCarsDetails extends CMyActiveRecord {
	public function tableName() {
        return 'users_cars_details';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article, brand', 'required'),
            array('user_id, car_id', 'numerical', 'integerOnly' => true),
            array('article, brand', 'length', 'max' => 100),
            array('name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, car_id, brand, article, name', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'brand' => Yii::t('userControlDetail', 'Brand'),
            'article' => Yii::t('userControlDetail', 'Article'),
            'name' => Yii::t('userControlDetail', 'Name'),
            'price' => Yii::t('userControlDetail', 'Price'),
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('car_id', $this->car_id);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('article', $this->article, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getButton() {
    	return '<a href="'.Yii::app()->createUrl('/detailSearchNew/default/search', array('article' => $this->article)).'" class="btn" target="_blank">'.Yii::t('userControlDetail', 'Price').'</button>';
    }
}
?>