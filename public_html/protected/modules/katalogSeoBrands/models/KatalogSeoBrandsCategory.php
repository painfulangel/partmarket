<?php
class KatalogSeoBrandsCategory extends CActiveRecord {
	public function tableName() {
        return 'katalog_seo_brands_category';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url, name', 'required'),
            array('url, name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, url', 'safe', 'on' => 'search'),
        );
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('katalogSeoBrands', 'Name'),
            'url' => Yii::t('katalogSeoBrands', 'URL')
        );
    }
}
?>