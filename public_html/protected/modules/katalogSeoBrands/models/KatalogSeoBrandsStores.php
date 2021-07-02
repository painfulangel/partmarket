<?php
class KatalogSeoBrandsStores extends CActiveRecord {
	public function tableName() {
        return 'katalog_seo_brands_stores';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('store_id', 'required'),
            array('store_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, store_id', 'safe', 'on' => 'search'),
        );
    }
}
?>