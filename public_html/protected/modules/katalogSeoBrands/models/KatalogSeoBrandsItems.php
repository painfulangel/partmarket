<?php
class KatalogSeoBrandsItems extends CActiveRecord {
	public function tableName() {
        return 'katalog_seo_brands_items';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('price_id, brand, article, name, price', 'required'),
            array('category_id, price_id, brand_id', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('brand, article, name', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, price_id, category_id, brand, brand_id, article, name, price', 'safe', 'on' => 'search'),
        );
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'article' => Yii::t('katalogSeoBrands', 'Article'),
            'brand' => Yii::t('katalogSeoBrands', 'Brand'),
            'name' => Yii::t('katalogSeoBrands', 'Name'),
            'price' => Yii::t('katalogSeoBrands', 'Price')
        );
    }

     public function relations() {
         return array(
            'cat'=>array(self::HAS_ONE, 'KatalogSeoBrandsCategory', array('id' => 'category_id')),
         );
    }

    public function buyButton($buy) {
        return '<a class="btn" href="'.Yii::app()->createAbsoluteUrl('/detailSearch/default/search', array('search_phrase' => $this->article)).'">'.$buy.'</a>';
    }
}
?>