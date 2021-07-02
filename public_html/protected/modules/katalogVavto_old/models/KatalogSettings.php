<?php
class KatalogSettings extends CMyActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CMyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KatalogVavtoBrands the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function getTranslatedFields() {
		return array(
			'seo1' => 'text',
			'seo2' => 'text',
		);
	}
	
	public function tableName() {
		return 'katalog_vavto_settings'.(empty($this->load_lang) ? '' : '_'.$this->load_lang);
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seo1, seo2', 'required'),
			array('seo1, seo2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seo1, seo2', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'seo1' => Yii::t('katalogVavto', 'SEO text 1'),
			'seo2' => Yii::t('katalogVavto', 'SEO text 2'),
		);
	}
}
?>