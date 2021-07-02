<?php

/**
 * This is the model class for table "prices_rules_groups".
 *
 * The followings are the available columns in table 'prices_rules_groups':
 * @property integer $id
 * @property integer $name
 */
class PricesRulesGroups extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_rules_groups';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required',),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
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
            'name' => Yii::t('pricegroups', 'Name'),
        );
    }

    public function getList() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT * FROM ' . $this->tableName())->queryAll();
        $dataArray = array();
        foreach ($data as $value) {
            $dataArray[$value['id']] = $value['name'];
        }
        return $dataArray;
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
        $criteria->compare('name', $this->name);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return PricesRulesGroups the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getPriceCathegoryList() {
        return array(
            '1' => Yii::t('pricegroups', 'Price group') . ' 1',
            '2' => Yii::t('pricegroups', 'Price group') . ' 2',
            '3' => Yii::t('pricegroups', 'Price group') . ' 3',
            '4' => Yii::t('pricegroups', 'Price group') . ' 4',
        );
    }

}
