<?php

/**
 * This is the model class for table "web_payments_robokassa".
 *
 * The followings are the available columns in table 'web_payments_robokassa':
 * @property integer $id
 * @property string $start_date
 * @property string $finish_date
 * @property integer $user_id
 * @property double $value
 * @property string $auth_key
 * @property string $total_value
 * @property string $description 
 */
class WebPaymentsRobokassa extends CMyActiveRecord
{

    public $commission = 0;

    public $type = 'Robokassa';

    public $system_login = '';

    public $system_password = '';

    public $system_extra_parametr = '';

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'web_payments_robokassa';
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->description = ($this->order_id) ? 'Order '.($this->prepay ? 'pre' : '').'payment (Robokassa)' : 'Account replenishment (Robokassa)'; // Yii::t('webPayments', 'Account replenishment (Robokassa)');
                
                $model = new WebPayments();
                $model->value = $this->value;
                $model->comment = $this->description;
                $model->model_type = $this->type;
                $model->method = 'Robokassa'; // 'Робокасса';
                
                $model->save();
                
                $this->start_date = time();
                $this->user_id = Yii::app()->user->id;
                $this->auth_key = $model->auth_key;
                $this->total_value = $this->commission * $this->value + $this->value;
            }
            
            if ($this->scenario == 'finish') {
                $this->finish_date = time();
                $model = WebPayments::model()->findByAttributes(array(
                    'auth_key' => $this->auth_key
                ));
                $model->scenario = 'finish';
                $model->save();
            }
            return true;
        }
        return false;
    }

    public function afterSave()
    {
        parent::afterSave();
        if ($this->isNewRecord) {
            $model = WebPayments::model()->findByAttributes(array(
                'auth_key' => $this->auth_key
            ));
            $model->model_id = $this->id;
            $model->save();
        }
    }

    public function afterConstruct()
    {
        parent::afterConstruct();
        $this->value = 0;
        $model = WebPaymentsSystem::model()->findByAttributes(array(
            'system_name' => $this->type
        ));
        if ($model != NULL) {
            $this->commission = $model->commission;
            $this->system_login = $model->system_login;
            $this->system_password = $model->system_password;
            $this->system_extra_parametr = $model->system_extra_parametr;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $model = WebPaymentsSystem::model()->findByAttributes(array(
            'system_name' => $this->type
        ));
        if ($model != NULL) {
            $this->commission = $model->commission;
            $this->system_login = $model->system_login;
            $this->system_password = $model->system_password;
            $this->system_extra_parametr = $model->system_extra_parametr;
        }
    }

    public function getSign($flag = 1)
    {
        // echo "$this->total_value:$this->id:$this->system_extra_parametr:Shp_item=$this->id ";
        // echo md5("$this->total_value:$this->id:$this->system_extra_parametr:Shp_item=$this->id");
        // // print_r($this);
        // echo md5("$this->total_value:$this->id:$this->system_extra_parametr:Shp_item=$this->id"). ' ';
        // echo md5("$this->total_value:$this->id:$this->system_password:Shp_item=$this->id"). ' ';
        
        // if ($flag == 6)
        // return md5("$this->total_value:$this->id:$this->system_extra_parametr");
        // if ($flag == 5)
        // return md5("$this->total_value:$this->id:$this->system_extra_parametr:Shp_item=$this->id");
        if ($flag == 3)
            return md5("$this->total_value:$this->id:$this->system_password:Shp_item=$this->id");
        else if ($flag == 1)
            return md5("$this->system_login:$this->total_value:$this->id:$this->system_password:Shp_item=$this->id");
        else {
            return md5("$this->total_value:$this->id:$this->system_extra_parametr:Shp_item=$this->id");
        }
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'user_id, order_id, prepay',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'value',
                'numerical'
            ),
            array(
                'start_date, finish_date',
                'length',
                'max' => 20
            ),
            array(
                'auth_key, total_value',
                'length',
                'max' => 255
            ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, start_date, finish_date, user_id, value, auth_key, total_value, order_id, prepay',
                'safe',
                'on' => 'search'
            )
        );
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'start_date' => 'Start Date',
            'finish_date' => 'Finish Date',
            'user_id' => 'User',
            'value' => Yii::t('webPayments', 'Amount to replenishment'),
            'auth_key' => 'Auth Key',
            'total_value' => Yii::t('webPayments', 'Amount (with the Commission)')
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
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();
        
        $criteria->compare('id', $this->id);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('finish_date', $this->finish_date, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('value', $this->value);
        $criteria->compare('auth_key', $this->auth_key, true);
        $criteria->compare('total_value', $this->total_value, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * 
     * @param string $className
     *            active record class name.
     * @return WebPaymentsRobokassa the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
