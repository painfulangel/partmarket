<?php
class Cities extends CMyActiveRecord {
    public function getTranslatedFields() {
        return array(
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'contacts' => 'text',
        );
    }

	public static function model($className = __CLASS__) {
    	return parent::model($className);
    }

	public function tableName() {
        return 'cities' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name, phone, email', 'length', 'max' => 255),
            array('by_default', 'numerical', 'integerOnly'=>true),
            array('id, name, coef, address, phone, email, contacts', 'safe'),
        );
    }

	public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('cities', 'Name'),
            'coef' => Yii::t('cities', 'Coeficient'),
            'address' => Yii::t('cities', 'Address'),
            'phone' => Yii::t('cities', 'Phone'),
            'email' => Yii::t('cities', 'E-mail'),
            'contacts' => Yii::t('cities', 'Contacts page'),
            'by_default' => Yii::t('cities', 'By default'),
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('by_default', $this->by_default);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public static function getInfo() {
        $city = null;

        if (defined('TURNON_CITIES') && TURNON_CITIES == true) {
            if (Yii::app()->user->isGuest) {
                if (array_key_exists('city', $_COOKIE)) {
                    $c = self::model()->findByPk(intval($_COOKIE['city']));
                    if (is_object($c)) $city = $c;
                }
            } else {
                $uid = Yii::app()->user->id;
                $model = UserProfile::model()->findByAttributes(array('uid' => $uid));

                if (!empty($model) && $model->city) {
                    $uc = self::model()->findByPk($model->city);
                    if (is_object($uc)) $city = $uc;
                }
            }

            if (!is_object($city)) $city = self::model()->findByAttributes(array('by_default' => 1));
        }
    
        return $city;
    }
}
?>