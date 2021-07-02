<?php

/**
 * This is the model class for table "katalog_vavto_cathegorias".
 *
 * The followings are the available columns in table 'katalog_vavto_cathegorias':
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property string $level
 * @property string $parent_id
 * @property integer $order
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property integer $active_state
 * @property string $image
 * @property string $short_title 
 * @property string $menu_image 
 * @property string $short_text
 * @property string $sub_image_class 
 * @property string $years
 * @property string $index_image 
 *
 * The followings are the available model relations:
 * @property KatalogVavtoItems[] $katalogVavtoItems
 */
class KatalogVavtoCathegoriasNet extends CMyActiveRecord {

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
            'short_title' => 'string',
            'short_text' => 'text',
            'years' => 'string',
//            ''=>'string',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_vavto_cathegorias' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'meta_title' => Yii::t('katalogVavto', 'Title'),
            'meta_description' => Yii::t('katalogVavto', 'page Description'),
            'meta_keywords' => Yii::t('katalogVavto', 'Keywords'),
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'parent_id' => Yii::t('katalogVavto', 'Parent'),
            'order' => 'Order',
            'title' => Yii::t('katalogVavto', 'Name'),
            'short_title' => Yii::t('katalogVavto', 'Short title'),
            'slug' => Yii::t('katalogVavto', 'Alias'),
            'text' => Yii::t('katalogVavto', 'Text'),
            'short_text' => Yii::t('katalogVavto', 'Text for alt'),
            'active_state' => Yii::t('katalogVavto', 'Enable'),
            'image' => Yii::t('katalogVavto', 'Picture'),
            '_image' => Yii::t('katalogVavto', 'Picture'),
            'index_image' => Yii::t('katalogVavto', 'The picture (on the main car)'),
            '_index_image' => Yii::t('katalogVavto', 'The picture (on the main car)'),
            'menu_image' => Yii::t('katalogVavto', 'Left menu style'),
            'sub_image_class' => Yii::t('katalogVavto', 'Sub style'),
            'years' => Yii::t('katalogVavto', 'Year'),
        );
    }

    public function behaviors() {
        return array();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogVavtoCathegorias the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
