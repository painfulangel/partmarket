<?php

/**
 * This is the model class for table "feedbacks".
 *
 * The followings are the available columns in table 'feedbacks':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property string $create_date
 * @property integer $active_state
 */
class Feedbacks extends CMyActiveRecord {

    public $verifyCode;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'feedbacks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email, text', 'required'),
            array('active_state', 'numerical', 'integerOnly' => true),
            array('name, email, text', 'length', 'max' => 255),
            array('create_date', 'length', 'max' => 20),
            array('email', 'email'),
            array('verifyCode', 'captcha',
                // авторизованным пользователям код можно не вводить
                'allowEmpty' => !Yii::app()->user->isGuest || !CCaptcha::checkRequirements() || !$this->isNewRecord,
            ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, email, text, create_date, active_state', 'safe', 'on' => 'search'),
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

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->active_state = 0;
                $message = new YiiMailMessage();
                $message->setBody("Пользователь " . $this->email . ' (' . $this->name . ')  оставил отзыв ' . $this->text, 'text/html');
                $message->setSubject('Новый отзыв на вашем сайте ' . Yii::app()->config->get('Site.SiteName'));
                $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
                $message->from = Yii::app()->config->get('Site.NoreplyEmail');
                Yii::app()->mail->send($message);
            }
            $this->create_date = time();
            return true;
        }
        return false;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('requests', 'Name'),
            'email' =>Yii::t('requests', 'Email') ,
            'text' => Yii::t('requests', 'Text'),
            'create_date' =>Yii::t('requests', 'Date') ,
            'active_state' => Yii::t('requests', 'Condition'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('active_state', $this->active_state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Feedbacks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function initUser() {

        if (!Yii::app()->user->isGuest) {
            $model = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
            $this->email = $model->email;
            $this->name = $model->getFullName();
        }
    }

}
