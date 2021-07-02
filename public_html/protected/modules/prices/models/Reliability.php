<?php

/**
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property integer $id
 * @property string $name
 * @property integer $count_state
 * @property integer $price_group_1
 * @property integer $price_group_2
 * @property integer $price_group_3
 * @property integer $price_group_4
 * @property integer $auto_delete_state
 * @property integer $delivery
 * @property string $supplier_inn
 * @property string $supplier
 * @property integer $currency
 * @property integer $search_state
 */
class Reliability extends CMyActiveRecord {

    public $_list = array();

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'reliability';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('done_orders,refuse_orders', 'numerical', 'integerOnly' => true),
            array('supplier_inn', 'length', 'max' => 255),
        );
    }

    public static function addDoneOrder($supplier) {
        $model = Reliability::model()->findByAttributes(array('supplier_inn' => $supplier));
        if ($model == NULL) {
            $model = new Reliability;
            $model->done_orders = 0;
        }
        $model->done_orders++;
        $model->save();
    }

    public static function addRefuseOrder($supplier) {
        $model = Reliability::model()->findByAttributes(array('supplier_inn' => $supplier));
        if ($model == NULL) {
            $model = new Reliability;
            $model->done_orders = 1;
            $model->refuse_orders = 0;
            $model->supplier_inn = $supplier;
        }
        $model->refuse_orders++;
//        print_r($model);
        $model->save();
    }

    public function getReliability($supplier) {
        if (empty($this->_list)) {
            $db = Yii::app()->db;
            $sql = 'SELECT * FROM `' . $this->tableName() . '` `t`  ';

            $data = $db->createCommand($sql)->queryAll();
            foreach ($data as $row) {
                $this->_list[$row['supplier_inn']] = $this->getReliabilityValue($row['done_orders'], $row['refuse_orders']);
            }
        }
        if (isset($this->_list[$supplier]))
            return $this->_list[$supplier];
        else
            return 'н.д.';
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

    public function getReliabilityValue($done, $refuse) {
        if ($done != 0)
            return 100 - $refuse / $done * 100;
        return 'н.д.';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('prices', 'Name'),
            'count_state' => Yii::t('prices', 'Consider the number of in storage'),
            'search_state' => Yii::t('prices', 'Show price list on the website'),
            'price_group_1' => Yii::t('prices', 'Price group') . ' 1',
            'price_group_2' => Yii::t('prices', 'Price group') . ' 2',
            'price_group_3' => Yii::t('prices', 'Price group') . ' 3',
            'price_group_4' => Yii::t('prices', 'Price group') . ' 4',
            'delivery' => Yii::t('prices', 'Delivery date'),
            'supplier_inn' => Yii::t('prices', 'Supplier INN'),
            'supplier' => Yii::t('prices', 'Supplier'),
            'currency' => Yii::t('prices', 'Currency'),
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
        $criteria->compare('count_state', $this->count_state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Stores the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
