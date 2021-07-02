<?php

/**
 * This is the model class for table "w_to_cars".
 *
 * The followings are the available columns in table 'w_to_cars':
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $is_active
 * @property string $content
 * @property string $title
 * @property string $kwords
 * @property string $descr
 * @property string $img
 * @property integer $truck
 * @property string $seo_text
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property WToModels[] $wToModels
 */
class WToCars extends CMyActiveRecord {
//     public function getTranslatedFields() {
//        return array(
//            '',
//        );
//    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'w_to_cars' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sort, is_active, truck', 'numerical', 'integerOnly' => true),
            array('name, title, img, slug', 'length', 'max' => 127),
            array('kwords, descr', 'length', 'max' => 255),
            array('content, seo_text', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, sort, is_active, content, title, kwords, descr, img, truck, seo_text, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'wToModels' => array(self::HAS_MANY, 'WToModels', 'car_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('katalogTO', 'Brand'),
            'sort' => 'Sort',
            'is_active' => 'Is Active',
            'content' => 'Content',
            'title' => 'Title',
            'kwords' => 'Kwords',
            'descr' => 'Descr',
            'img' => 'Img',
            'truck' => 'Truck',
            'seo_text' => 'Seo Text',
            'slug' => 'Slug',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sort', $this->sort);
        $criteria->compare('is_active', $this->is_active);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('kwords', $this->kwords, true);
        $criteria->compare('descr', $this->descr, true);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('truck', $this->truck);
        $criteria->compare('seo_text', $this->seo_text, true);
        $criteria->compare('slug', $this->slug, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
            'sort' => array(
                'defaultOrder' => ' t.sort ASC, name ASC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return WToCars the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
