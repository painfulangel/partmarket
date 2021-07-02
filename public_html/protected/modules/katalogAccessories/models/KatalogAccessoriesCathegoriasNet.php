<?php

/**
 * This is the model class for table "katalog_accessories_cathegorias".
 *
 * The followings are the available columns in table 'katalog_accessories_cathegorias':
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
 * @property boolean $active_state 
 */
class KatalogAccessoriesCathegoriasNet extends CMyActiveRecord {

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
//            '',
//            '',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_accessories_cathegorias' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'meta_title' => Yii::t('katalogAccessories', 'Meta-header'),
            'meta_description' => Yii::t('katalogAccessories', 'Description page'),
            'meta_keywords' => Yii::t('katalogAccessories', 'Keywords'),
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'parent_id' => Yii::t('katalogAccessories', 'Procreator'),
            'order' => 'Order',
            'title' => Yii::t('katalogAccessories', 'Name'),
            'slug' => Yii::t('katalogAccessories', 'Alias'),
            'text' => Yii::t('katalogAccessories', 'Text'),
            'active_state' => Yii::t('katalogAccessories', 'Activity'),
        );
    }

    public function behaviors() {
        return array(
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogAccessoriesCathegorias the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
