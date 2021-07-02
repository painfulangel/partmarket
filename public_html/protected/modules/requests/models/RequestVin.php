<?php
/**
 * This is the model class for table "request_vin".
 *
 * The followings are the available columns in table 'request_vin':
 * @property integer $id
 * @property string $vin
 * @property string $car_model
 * @property integer $car_year
 * @property string $engine_model
 * @property string $body
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $text
 * @property integer $work_state
 * @property string $comment
 */
class RequestVin extends CMyActiveRecord
{
    public $verifyCode;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_vin';
    }

    public function afterSave()
    {
        parent::afterSave();
        if ($this->isNewRecord) {
            $message = new YiiMailMessage();
            $atr = $this->getAttributes();
            $atr_text = '';
            foreach ($atr as $k => $v) {
            	if ($v == 'NOW()') $v = date('d.m.Y H:i:s');
                $atr_text .= $this->getAttributeLabel($k) . ': ' . $v . '<br>';
            }
            $message->setBody($atr_text . 'Запрос создан ' . date('d.m.Y H:i:s') . '.', 'text/html');
            $message->setSubject('VIN-запрос с сайта ' . Yii::app()->config->get('Site.SiteName'));
            $emails = explode(',', $this->email);
            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            $recipient_count = Yii::app()->mail->send($message);
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vin, car_model, car_year, engine_model, body, name, email, phone, text', 'required'),
            array('car_year, work_state', 'numerical', 'integerOnly' => true),
            array('vin, car_model, engine_model, body, name, email, phone', 'length', 'max' => 255),
            array('comment', 'safe'),
            array('email', 'email'),
            /*array('verifyCode', 'captcha',
                // авторизованным пользователям код можно не вводить
                'allowEmpty' => !Yii::app()->user->isGuest || !CCaptcha::checkRequirements() || !$this->isNewRecord,
            ),*/
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, vin, car_model, car_year, engine_model, body, name, email, phone, text, work_state, comment, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'vin' => Yii::t('requests', 'VIN'),
            'car_model' => Yii::t('requests', 'Mark, Model'),
            'car_year' => Yii::t('requests', 'Year of production'),
            'engine_model' => Yii::t('requests', 'Engine model'),
            'body' => Yii::t('requests', 'Car body'),
            'name' => Yii::t('requests', 'Name'),
            'email' => Yii::t('requests', 'Email'),
            'phone' => Yii::t('requests', 'Phone number'),
            'text' => Yii::t('requests', 'Text of inquiry'),
            'work_state' => Yii::t('requests', 'Processed'),
            'comment' => Yii::t('requests', 'Comment'),
        	'date_create' => Yii::t('requests', 'Date_create'),
            'verifyCode' => Yii::t('requests', 'Verification code'),
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('vin', $this->vin, true);
        $criteria->compare('car_model', $this->car_model, true);
        $criteria->compare('car_year', $this->car_year);
        $criteria->compare('engine_model', $this->engine_model, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('work_state', $this->work_state);
        $criteria->compare('comment', $this->comment, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestVin the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function initUser()
    {

        if (!Yii::app()->user->isGuest) {
            $model = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
            $this->email = $model->email;
            $this->name = $model->getFullName();
            $this->phone = $model->phone;
        }
    }
}