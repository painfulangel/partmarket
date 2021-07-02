<?php

/**
 * This is the model class for table "w_to_types".
 *
 * The followings are the available columns in table 'w_to_types':
 * @property integer $id
 * @property integer $model_id
 * @property string $name
 * @property integer $sort
 * @property integer $is_active
 * @property string $content
 * @property string $title
 * @property string $kwords
 * @property string $descr
 * @property string $img
 * @property string $mod
 * @property string $engine
 * @property string $engine_model
 * @property string $engine_obj
 * @property string $engine_horse
 * @property string $type_year
 * @property string $seo_text
 * @property string $tecdoc_url
 * @property string $tecdoc_id
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property WTo[] $wTos
 * @property WToModels $model
 */
class WToTypes extends CMyActiveRecord {

//     public function getTranslatedFields() {
//        return array(
//            '',
//        );
//    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'w_to_types' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model_id, sort, is_active', 'numerical', 'integerOnly' => true),
            array('name, title, img, mod, engine, engine_model, engine_obj, engine_horse, type_year, tecdoc_url, tecdoc_id, slug', 'length', 'max' => 127),
            array('kwords, descr', 'length', 'max' => 255),
            array('content, seo_text', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, model_id, name, sort, is_active, content, title, kwords, descr, img, mod, engine, engine_model, engine_obj, engine_horse, type_year, seo_text, tecdoc_url, tecdoc_id, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'wTos' => array(self::HAS_MANY, 'WTo', 'type_id'),
            'model' => array(self::BELONGS_TO, 'WToModels', 'model_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'model_id' => 'Model',
            'name' => Yii::t('katalogTO', 'Name of modification'),
            'sort' => 'Sort',
            'is_active' => 'Is Active',
            'content' => 'Content',
            'title' => 'Title',
            'kwords' => 'Kwords',
            'descr' => 'Descr',
            'img' => 'Img',
            'mod' => 'Mod',
            'engine' => Yii::t('katalogTO', 'Engine'),
            'engine_model' => 'Engine Model',
            'engine_obj' => 'Engine Obj',
            'engine_horse' => 'Engine Horse',
            'type_year' => Yii::t('katalogTO', 'Delivery date'),
            'seo_text' => 'Seo Text',
            'tecdoc_url' => 'Tecdoc Url',
            'tecdoc_id' => 'Tecdoc',
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
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sort', $this->sort);
        $criteria->compare('is_active', $this->is_active);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('kwords', $this->kwords, true);
        $criteria->compare('descr', $this->descr, true);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('mod', $this->mod, true);
        $criteria->compare('engine', $this->engine, true);
        $criteria->compare('engine_model', $this->engine_model, true);
        $criteria->compare('engine_obj', $this->engine_obj, true);
        $criteria->compare('engine_horse', $this->engine_horse, true);
        $criteria->compare('type_year', $this->type_year, true);
        $criteria->compare('seo_text', $this->seo_text, true);
        $criteria->compare('tecdoc_url', $this->tecdoc_url, true);
        $criteria->compare('tecdoc_id', $this->tecdoc_id, true);
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
     * @return WToTypes the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
