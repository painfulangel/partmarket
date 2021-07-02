<?php

/**
 * This is the model class for table "users_cars".
 *
 * The followings are the available columns in table 'users_cars':
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property string $brand
 * @property string $vin
 * @property string $year
 * @property string $body
 * @property string $engine_v
 * @property string $engine_t
 * @property string $transsmition
 * @property string $suggestion
 * @property string $comment
 */
class UsersCars extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users_cars';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model, vin, brand', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('model, brand, vin, year, body, engine_v, engine_t, transsmition, suggestion', 'length', 'max' => 255),
            array('comment', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, model, brand, vin, year, body, engine_v, engine_t, transsmition, suggestion, comment', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.

        return array(
            'User' => array(self::HAS_ONE, 'UserProfile', array('uid' => 'user_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => Yii::t('userControl', 'User'),
            'model' => Yii::t('userControl', 'Brand'),
            'brand' => Yii::t('userControl', 'Model'),
            'vin' => Yii::t('userControl', 'VIN'),
            'year' => Yii::t('userControl', 'Year of manufacture'),
            'body' => Yii::t('userControl', 'Car body'),
            'engine_v' => Yii::t('userControl', 'Engine capacity'),
            'engine_t' => Yii::t('userControl', 'Engine\'s type'),
            'transsmition' => Yii::t('userControl', 'Transmission'),
            'suggestion' => Yii::t('userControl', 'Offer administrator'),
            'comment' => Yii::t('userControl', 'Comment'),
        );
    }

    function beforeSave() {
        if (parent::beforeSave()) {
            $this->user_id = Yii::app()->user->id;
            return true;
        }
        return false;
    }

    public function beforeDelete() {
        UsersCarsDetails::model()->deleteAll('car_id = '.$this->primaryKey);
        
        return parent::beforeDelete();
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('vin', $this->vin, true);
        $criteria->compare('year', $this->year, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('engine_v', $this->engine_v, true);
        $criteria->compare('engine_t', $this->engine_t, true);
        $criteria->compare('transsmition', $this->transsmition, true);
        $criteria->compare('suggestion', $this->suggestion, true);
        $criteria->compare('comment', $this->comment, true);

        $criteria->order = 'id DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return UsersCars the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
