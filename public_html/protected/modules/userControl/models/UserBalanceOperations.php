<?php

/**
 * This is the model class for table "user_balance_operations".
 *
 * The followings are the available columns in table 'user_balance_operations':
 * @property integer $id
 * @property integer $user_id
 * @property double $value
 * @property string $create_time
 * @property string $comment
 * @property double $balance 
 * @property integer $order_id 
 */
class UserBalanceOperations extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_balance_operations';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, order_id,update_status', 'numerical', 'integerOnly' => true),
            array('value, balance', 'numerical'),
            array('create_time', 'length', 'max' => 20),
            array('comment', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, value, balance, create_time, comment', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            $this->update_status = 1;
            if ($this->scenario == '1c') {
                $this->update_status = 0;
            }
            if (empty($this->create_time))
                $this->create_time = time();
            $this->balance = $this->getBalance() + $this->value;
            return true;
        }
        return false;
    }

    public function afterSave() {
        parent::afterSave();
        $user = UserProfile::model()->findByAttributes(array('uid' => $this->user_id));
        $user->updateBalance();
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
            'user_id' => Yii::t('userControl', 'User'),
            'value' => Yii::t('userControl', 'Amount'),
            'create_time' => Yii::t('userControl', 'Date'),
            'comment' => Yii::t('userControl', 'Reason'),
            'balance' => Yii::t('userControl', 'Stock'),
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
        $criteria->compare('value', $this->value);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('balance', $this->balance);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public function getBalance() {
        $db = Yii::app()->db;
        $sql = 'SELECT balance FROM `' . UserProfile::model()->tableName() . '` `t`  '
                . "WHERE uid='$this->user_id' ";
        return $db->createCommand($sql)->queryScalar();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserBalanceOperations the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getNewModel($user) {
        $model = new UserBalanceOperations;
        if (isset($user->uid))
            $model->user_id = $user->uid;
        else if (isset($user->user_id))
            $model->user_id = $user->user_id;
        return $model;
    }

}
