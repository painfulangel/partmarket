<?php
class KatalogSeoBrandsBrands extends CActiveRecord {
	public function tableName() {
        return 'katalog_seo_brands_brands';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('brand', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, brand, main', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('katalogSeoBrands', 'Brand'),
            'main' => Yii::t('katalogSeoBrands', 'Main'),
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('main', $this->main);

        $criteria->order = 'brand ASC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }
}
?>