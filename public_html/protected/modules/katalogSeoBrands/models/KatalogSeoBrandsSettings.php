<?php
class KatalogSeoBrandsSettings extends CActiveRecord {
	public function tableName() {
        return 'katalog_seo_brands_settings';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cron_count, pricegroup, url', 'required'),
            array('cron_count, show_widget, index_active, pricegroup', 'numerical', 'integerOnly' => true),
            array('url', 'length', 'max' => 255),
            array('index_title, index_keywords, index_description, index_h1, index_text, brand_title, brand_keywords, brand_description, brand_h1, brand_text, brand_buy, category_title, category_keywords, category_description, category_h1, category_text, category_buy, article_title, article_keywords, article_description, article_h1, article_content, article_text, article_buy', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, index_active, pricegroup, url', 'safe', 'on' => 'search'),
        );
    }
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
            'cron_count' => Yii::t('katalogSeoBrands', 'Cron count'),
            'show_widget' => Yii::t('katalogSeoBrands', 'Show widget'),
			'index_active' => Yii::t('katalogSeoBrands', 'Active index'),
			'pricegroup' => Yii::t('katalogSeoBrands', 'Pricegroup'),
			'url' => Yii::t('katalogSeoBrands', 'URL'),
            'index_title' => Yii::t('katalogSeoBrands', 'Title'), 
            'index_keywords' => Yii::t('katalogSeoBrands', 'Keywords'), 
            'index_description' => Yii::t('katalogSeoBrands', 'Description'), 
            'index_h1' => Yii::t('katalogSeoBrands', 'h1'), 
            'index_text' => Yii::t('katalogSeoBrands', 'Bottom text'),

            'brand_title' => Yii::t('katalogSeoBrands', 'Title'), 
            'brand_keywords' => Yii::t('katalogSeoBrands', 'Keywords'), 
            'brand_description' => Yii::t('katalogSeoBrands', 'Description'), 
            'brand_h1' => Yii::t('katalogSeoBrands', 'h1'), 
            'brand_text' => Yii::t('katalogSeoBrands', 'Bottom text'), 
            'brand_buy' => Yii::t('katalogSeoBrands', 'Buy button text'),

            'category_title' => Yii::t('katalogSeoBrands', 'Title'), 
            'category_keywords' => Yii::t('katalogSeoBrands', 'Keywords'), 
            'category_description' => Yii::t('katalogSeoBrands', 'Description'), 
            'category_h1' => Yii::t('katalogSeoBrands', 'h1'), 
            'category_text' => Yii::t('katalogSeoBrands', 'Bottom text'), 
            'category_buy' => Yii::t('katalogSeoBrands', 'Buy button text'),

            'article_title' => Yii::t('katalogSeoBrands', 'Title'), 
            'article_keywords' => Yii::t('katalogSeoBrands', 'Keywords'), 
            'article_description' => Yii::t('katalogSeoBrands', 'Description'), 
            'article_h1' => Yii::t('katalogSeoBrands', 'h1'), 
            'article_content' => Yii::t('katalogSeoBrands', 'Article content'), 
            'article_text' => Yii::t('katalogSeoBrands', 'Bottom text'), 
            'article_buy' => Yii::t('katalogSeoBrands', 'Buy button text'),
		);
	}

    public function replaceBrand($brand) {
        $this->brand_title = str_replace('@brand@', $brand, $this->brand_title);
        $this->brand_keywords = str_replace('@brand@', $brand, $this->brand_keywords);
        $this->brand_description = str_replace('@brand@', $brand, $this->brand_description);
        $this->brand_h1 = str_replace('@brand@', $brand, $this->brand_h1);
        $this->brand_text = str_replace('@brand@', $brand, $this->brand_text);
        $this->brand_buy = str_replace('@brand@', $brand, $this->brand_buy);
    }

    public function replaceBrandCategory($brand, $category) {
        $this->brand_title = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_title);
        $this->brand_keywords = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_keywords);
        $this->brand_description = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_description);
        $this->brand_h1 = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_h1);
        $this->brand_text = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_text);
        $this->brand_buy = str_replace(array('@brand@', '@category@'), array($brand, $category), $this->category_buy);
    }

    public function replaceAll($item) {
        $price = Yii::app()->getModule('prices')->getPriceFormatFunction($item->price);

        $this->article_title = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_title);
        $this->article_keywords = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_keywords);
        $this->article_description = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_description);
        $this->article_h1 = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_h1);
        $this->article_content = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_content);
        $this->article_text = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_text);
        $this->article_buy = str_replace(array('@article@', '@brand@', '@description@', '@price@'), array($item->article, $item->brand, $item->name, $price), $this->article_buy);
    }
}
?>