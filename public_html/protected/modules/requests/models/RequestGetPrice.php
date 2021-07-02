<?php
/**
 * This is the model class for table "request_get_price".
 *
 * The followings are the available columns in table 'request_get_price':
 * @property integer $id
 * @property string $vin
 * @property string $car_model
 * @property string $car_brand
 * @property integer $car_year
 * @property string $email_phone
 * @property integer $work_state
 * @property string $detail
 * @property string $comment
 */
class RequestGetPrice extends CMyActiveRecord {
    public $verifyCode;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'request_get_price';
    }

    public function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
        	if (strpos($this->email_phone, '@') !== false) {
	            $message = new YiiMailMessage();
	            $atr = $this->getAttributes();
	            $atr_text = '';
	            foreach ($atr as $k => $v) {
	                $atr_text.=$this->getAttributeLabel($k) . ': ' . $v . '<br>';
	            }
	            $message->setBody($atr_text . 'Запрос создан ' . date('d.m.Y H:i:s') . '.', 'text/html');
	            $message->setSubject('Запрос на цену с сайта ' . Yii::app()->config->get('Site.SiteName'));
	            $emails = explode(',', $this->email_phone);
	            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
	            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
	            $recipient_count = Yii::app()->mail->send($message);
        	}
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email_phone, detail', 'required'),
            array('car_year, work_state', 'numerical', 'integerOnly' => true),
            array('vin, brand, car_model, car_brand, email_phone, detail', 'length', 'max' => 255),
            array('comment', 'safe'),
            /*array('verifyCode', 'captcha',
                // авторизованным пользователям код можно не вводить
                'allowEmpty' => !Yii::app()->user->isGuest || !CCaptcha::checkRequirements() || !$this->isNewRecord,
            ),*/
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, vin, brand, car_model, car_brand, car_year, email_phone, work_state, detail, comment, date_create', 'safe', 'on' => 'search'),
        );
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
            'vin' => Yii::t('requests', 'VIN'),
        	'brand' => Yii::t('requests', 'Brand'),
            'car_model' =>Yii::t('requests', 'Model') ,
            'car_brand' => Yii::t('requests', 'Mark'),
            'car_year' => Yii::t('requests', 'Year of production'),
            'email_phone' => Yii::t('requests', 'Email or Phone number'),
            'work_state' => Yii::t('requests', 'Processed'),
            'detail' => Yii::t('requests', 'Spare part'),
            'comment' => Yii::t('requests', 'Comment'),
        	'date_create' => Yii::t('requests', 'Date_create'),
            'verifyCode' =>Yii::t('requests', 'Verification code'),
        );
    }
    
    public function behaviors() {
    	return array(
    		'CTimestampBehavior' => array(
    			'class' => 'zii.behaviors.CTimestampBehavior',
    			'createAttribute' => 'date_create',
    			'updateAttribute' => null,
    			'setUpdateOnCreate' => false,
    		),
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
        $criteria->compare('vin', $this->vin, true);
        $criteria->compare('car_model', $this->car_model, true);
        $criteria->compare('car_brand', $this->car_brand, true);
        $criteria->compare('car_year', $this->car_year);
        $criteria->compare('email_phone', $this->email_phone, true);
        $criteria->compare('work_state', $this->work_state);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('comment', $this->comment, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestGetPrice the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function initUser() {
        if (!Yii::app()->user->isGuest) {
            $model = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
            $this->email_phone = $model->email . ';' . $model->phone;
        }
    }
}