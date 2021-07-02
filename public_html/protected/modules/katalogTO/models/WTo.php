<?php

/**
 * This is the model class for table "w_to".
 *
 * The followings are the available columns in table 'w_to':
 * @property integer $id
 * @property integer $type_id
 * @property string $descr
 * @property integer $box
 * @property string $comment
 * @property string $article
 * @property string $search
 * @property integer $brand_id
 * @property string $seo_title
 * @property string $seo_kwords
 * @property string $seo_descr
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property WToTypes $type
 */
class WTo extends CMyActiveRecord {

    public $count_store_items = 0;

//    public function getTranslatedFields() {
//        return array(
////            ''=>'string',
////            ''=>'string',
////            ''=>'string',
////            ''=>'string',
////            ''=>'string',
////            ''=>'string',
//        );
//    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'w_to' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, box, brand_id', 'numerical', 'integerOnly' => true),
            array('descr, comment, seo_title, seo_kwords, seo_descr', 'length', 'max' => 255),
            array('article, search, slug', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type_id, descr, box, comment, article, search, brand_id, seo_title, seo_kwords, seo_descr, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'type' => array(self::BELONGS_TO, 'WToTypes', 'type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'type_id' => 'Type',
            'descr' => Yii::t('katalogTO', 'Part title'),
            'box' => Yii::t('katalogTO', 'Number for TI'),
            'comment' => Yii::t('katalogTO', 'Add. description'),
            'article' => Yii::t('katalogTO', 'Original number'),
            'search' => Yii::t('katalogTO', 'Original number'),
            'brand_id' => 'Brand',
            'seo_title' => 'Seo Title',
            'seo_kwords' => 'Seo Kwords',
            'seo_descr' => 'Seo Descr',
            'slug' => 'Slug',
            'count_store_items' => Yii::t('katalogTO', 'Offers'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
//        $criteria->select.=', (SELECT COUNT(*) FROM `prices_data` WHERE article=t.article ) as count_store_items';
        $criteria->compare('id', $this->id);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('descr', $this->descr, true);
        $criteria->compare('box', $this->box);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('article', $this->article, true);
        $criteria->compare('search', $this->search, true);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('seo_title', $this->seo_title, true);
        $criteria->compare('seo_kwords', $this->seo_kwords, true);
        $criteria->compare('seo_descr', $this->seo_descr, true);
        $criteria->compare('slug', $this->slug, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return WTo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
