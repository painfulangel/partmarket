<?php

/**
 * This is the model class for table "prices_rules".
 *
 * The followings are the available columns in table 'prices_rules':
 * @property integer $id
 * @property integer $group_id
 * @property double $top_value
 * @property double $koeficient
 * @property string $brand
 */
class PricesRules extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_rules';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('group_id', 'required'),
            array('group_id', 'numerical', 'integerOnly' => true),
            array('top_value, koeficient', 'numerical'),
            array('brand', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, group_id, top_value, koeficient, brand', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if (empty($this->brand))
                $this->brand = 0;
            if (empty($this->top_value))
                $this->top_value = 0;
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'group_id' => Yii::t('pricegroups', 'Group'),
            'top_value' => Yii::t('pricegroups', 'Upper bound of the price'),
            'koeficient' => Yii::t('pricegroups', 'Ratio'),
            'brand' => Yii::t('pricegroups', 'Brand,'),
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
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('top_value', $this->top_value);
        $criteria->compare('koeficient', $this->koeficient);
        $criteria->compare('brand', $this->brand, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
            'sort' => array(
                'defaultOrder' => array('group_id' => true,
                    'top_value' => false),
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return PricesRules the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
