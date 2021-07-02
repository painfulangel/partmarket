<?php

/**
 * This is the model class for table "web_payments".
 *
 * The followings are the available columns in table 'web_payments':
 * @property integer $id
 * @property string $method
 * @property string $start_date
 * @property string $finish_date
 * @property integer $user_id
 * @property double $value
 * @property string $comment
 * @property string $auth_key
 * @property integer $model_id
 * @property string $model_type
 */
class WebPayments extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'web_payments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, model_id', 'numerical', 'integerOnly' => true),
            array('value', 'numerical'),
            array('method, comment, auth_key', 'length', 'max' => 255),
            array('start_date, finish_date', 'length', 'max' => 20),
            array('model_type', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, method, start_date, finish_date, user_id, value, comment, auth_key, model_id, model_type', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->start_date = time();
                $this->auth_key = md5(time() . $this->value . 'md5');
                $this->user_id = Yii::app()->user->id;
            }
            if ($this->scenario == 'finish') {
                $this->finish_date = time();
                $model = UserProfile::model()->findByAttributes(array('uid' => $this->user_id));
                $model->addMoneyOperation($this->value, $this->comment);
            }
            return true;
        }
        return false;
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
            'method' => Yii::t('webPayments', 'Payment method'),
            'start_date' => Yii::t('webPayments', 'Starting date of payment'),
            'finish_date' => Yii::t('webPayments', 'End date of the operation'),
            'user_id' => Yii::t('webPayments', 'User'),
            'value' => Yii::t('webPayments', 'Amount'),
            'comment' => Yii::t('webPayments', 'Comment'),
            'auth_key' => 'token',
            'model_id' => Yii::t('webPayments', 'Id transactions in the payment system'),
            'model_type' => 'Model Type',
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

        $criteria->with = array('User' => array('together' => true, 'alias' => 'u'));

        $criteria->compare('id', $this->id);
        $criteria->compare('method', $this->method, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('finish_date', $this->finish_date, true);
        if (is_int($this->user_id))
            $criteria->compare('user_id', $this->user_id);
        else {
            $criteria->compare('concat(u.second_name,\' \',u.first_name)', $this->user_id, true);
        }
        $criteria->compare('value', $this->value);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('auth_key', $this->auth_key, true);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('model_type', $this->model_type, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return WebPayments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
