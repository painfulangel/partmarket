<?php

/**
 * This is the model class for table "users_api_access".
 *
 * The followings are the available columns in table 'users_api_access':
 * @property integer $id
 * @property integer $user_id
 * @property string $access_token
 * @property integer $active_state
 *
 * The followings are the available model relations:
 * @property UserProfile $user
 */
class UsersApiAccess extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users_api_access';
    }

    public function __construct($scenario = 'insert') {
        parent::__construct($scenario);
        $this->access_token = md5(time() . 'secret');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, active_state', 'numerical', 'integerOnly' => true),
            array('access_token', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, access_token, active_state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'UserProfile', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => Yii::t('userControl', 'user ID'),
            'access_token' => Yii::t('userControl', 'Access key'),
            'active_state' => Yii::t('userControl', 'Is active'),
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
        $criteria->compare('user_id', $this->user_id);
//        $criteria->compare('access_token', $this->access_token, true);
        $criteria->compare('active_state', $this->active_state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return UsersApiAccess the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
