<?php

/**
 * This is the model class for table "menus".
 *
 * The followings are the available columns in table 'menus':
 * @property integer $id
 * @property integer $order
 * @property string $menu_type
 * @property string $menu_value
 * @property string $echo_position
 * @property string $title
 * @property integer $visible
 */
class Menus extends CMyActiveRecord {

    public function getTranslatedFields() {
        return array(
            'title' => 'string',
                //            '' => 'string',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menus' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('menu_type, echo_position', 'required'),
            array('order, visible', 'numerical', 'integerOnly' => true),
            array('menu_type', 'length', 'max' => 45),
            array('menu_value, title', 'length', 'max' => 255),
            array('echo_position', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, order, menu_type, menu_value, echo_position, title, visible', 'safe', 'on' => 'search'),
        );
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            
            if (empty($this->load_lang)&&$this->isNewRecord) {
                $this->order = Yii::app()->db->createCommand("SELECT {$this->tableName()}.order FROM `{$this->tableName()}` ORDER BY `order` DESC LIMIT 1")->queryScalar() + 1;
            }
            return true;
        }
        return false;
    }

    public function afterSave() {
        parent::afterSave();
        Yii::app()->menu->updateMenu();
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
            'order' => 'Order',
            'menu_type' => Yii::t('menu', 'Menu type'),
            'menu_value' => Yii::t('menu', 'Value'),
            'echo_position' => Yii::t('menu', 'Position display'),
            'title' => Yii::t('menu', 'Text'),
            'visible' => Yii::t('menu', 'Only for authorized users'),
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
        $criteria->compare('order', $this->order);
        $criteria->compare('menu_type', $this->menu_type, true);
        $criteria->compare('menu_value', $this->menu_value, true);
        $criteria->compare('echo_position', $this->echo_position, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('visible', $this->visible);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 9999,
            ),
            'sort' => array(
                'defaultOrder' => 't.order asc',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Menus the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
