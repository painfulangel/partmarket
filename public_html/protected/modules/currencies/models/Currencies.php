<?php
/**
 * This is the model class for table "currencies".
 *
 * The followings are the available columns in table 'currencies':
 * @property integer $id
 * @property string $name
 * @property double $exchange
 * @property string $marker
 * @property integer $visibility_state
 */
class Currencies extends CMyActiveRecord {
    public function getTranslatedFields() {
        return array(
            'name' => 'string',
            'marker' => 'string',
        );
    }

    public function afterSave() {
        parent::afterSave();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'currencies' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, marker', 'required'),
            array('visibility_state, basic', 'numerical', 'integerOnly' => true),
            array('exchange, percent', 'numerical'),
            array('name', 'length', 'max' => 45),
            array('marker', 'length', 'max' => 5),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, exchange, percent, marker, visibility_state, basic', 'safe', 'on' => 'search'),
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
            'name' => Yii::t('currencies', 'Name'),
            'exchange' => Yii::t('currencies', 'Rate'),
        	'percent' => Yii::t('currencies', 'Percent added to rate'),
            'marker' => Yii::t('currencies', 'Token'),
            'visibility_state' => Yii::t('currencies', 'Visible to users'),
        	'basic' => Yii::t('currencies', 'Basic'),
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
        $criteria->compare('exchange', $this->exchange);
        $criteria->compare('marker', $this->marker, true);
        $criteria->compare('visibility_state', $this->visibility_state);
        $criteria->compare('basic', $this->basic);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Currencies the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getCurrencyList() {
    	$list = array();
    	
    	$items = self::model()->findAll(array('condition' => 'visibility_state = 1', 'order' => 'basic DESC'));
    	$count = count($items);
    	for ($i = 0; $i < $count; $i ++) {
    		$list[$items[$i]->primaryKey] = $items[$i]->name;
    	}
    	
    	return $list;
    }
}