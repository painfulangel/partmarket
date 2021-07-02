<?php

/**
 * This is the model class for table "w_to_models".
 *
 * The followings are the available columns in table 'w_to_models':
 * @property integer $id
 * @property integer $car_id
 * @property string $name
 * @property integer $sort
 * @property integer $is_active
 * @property string $content
 * @property string $title
 * @property string $kwords
 * @property string $descr
 * @property string $img
 * @property string $seo_text
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property WToCars $car
 * @property WToTypes[] $wToTypes
 */
class WToModels extends CMyActiveRecord {

//     public function getTranslatedFields() {
//        return array(
//            '',
//        );
//    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'w_to_models' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('car_id, sort, is_active', 'numerical', 'integerOnly' => true),
            array('name, title, img, slug', 'length', 'max' => 127),
            array('kwords, descr', 'length', 'max' => 255),
            array('seo_text', 'length', 'max' => 45),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, car_id, name, sort, is_active, content, title, kwords, descr, img, seo_text, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'car' => array(self::BELONGS_TO, 'WToCars', 'car_id'),
            'wToTypes' => array(self::HAS_MANY, 'WToTypes', 'model_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'car_id' => 'Car',
            'name' => Yii::t('katalogTO', 'Model'),
            'sort' => 'Sort',
            'is_active' => 'Is Active',
            'content' => Yii::t('katalogTO', 'Delivery date'),
            'title' => 'Title',
            'kwords' => 'Kwords',
            'descr' => 'Descr',
            'img' => 'Img',
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
        $criteria->compare('car_id', $this->car_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sort', $this->sort);
        $criteria->compare('is_active', $this->is_active);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('kwords', $this->kwords, true);
        $criteria->compare('descr', $this->descr, true);
        $criteria->compare('img', $this->img, true);
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
     * @return WToModels the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
