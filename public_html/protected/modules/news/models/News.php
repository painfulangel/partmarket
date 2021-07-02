<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $short_title
 * @property string $short_text
 * @property string $create_time
 * @property integer $user_id
 * @property string $link
 * @property string $keywords
 * @property string $description
 * @property integer $active_state
 * @property integer $visibility_state
 */
class News extends CMyActiveRecord {

    public function getTranslatedFields() {
        return array(
            'title' => 'string',
            'text' => 'text',
            'short_title' => 'string',
            'short_text' => 'text',
            'keywords' => 'string',
            'description' => 'string',
                //            '' => 'string',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'news' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, text, short_title, link', 'required'),
            //array('link', 'unique'),
            array('user_id, active_state, visibility_state', 'numerical', 'integerOnly' => true),
            array('title, keywords, description', 'length', 'max' => 255),
            array('short_title, link', 'length', 'max' => 127),
            array('create_time', 'length', 'max' => 20),
            array('short_text', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, text, short_title, short_text, create_time, user_id, link, keywords, description, active_state, visibility_state', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if (empty($this->load_lang)) {
                // var_dump($this);
                if ($this->isNewRecord)
                    $this->create_time = time();
                if (empty($this->link))
                    $this->link = CMyTranslit::getText($this->title);
                $this->user_id = 0;
            }
            return true;
        } else
            return false;
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
            'title' => Yii::t('news', 'Title'),
            'text' => Yii::t('news', 'Text'),
            'short_title' => Yii::t('news', 'Short title'),
            'short_text' => Yii::t('news', 'Preview'),
            'create_time' => Yii::t('news', 'Publication date'),
            'user_id' => 'User',
            'link' => Yii::t('news', 'Link'),
            'keywords' => Yii::t('news', 'Keywords (meta tag)'),
            'description' => Yii::t('news', 'Description (meta tag)'),
            'active_state' => Yii::t('news', 'Active'),
            'visibility_state' => Yii::t('news', 'Visibility for all'),
        );
    }

    public function scopes() {
        if (empty($this->load_lang)) {
            return array(
                'published' => array(
                    'condition' => 'active_state = 1',
                ),
            );
        } else {
            return array();
        }
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('short_title', $this->short_title, true);
        $criteria->compare('short_text', $this->short_text, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('keywords', $this->keywords, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('visibility_state', $this->visibility_state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return News the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
