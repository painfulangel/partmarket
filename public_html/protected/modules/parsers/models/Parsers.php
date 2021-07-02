<?php

/**
 * This is the model class for table "parsers".
 *
 * The followings are the available columns in table 'parsers':
 * @property integer $id
 * @property string $name
 * @property string $parser_class
 * @property integer $price_group_1
 * @property integer $price_group_2
 * @property integer $price_group_3
 * @property integer $price_group_4
 * @property integer $active_state
 * @property integer $delivery
 * @property string $supplier_inn
 * @property string $supplier
 * @property string $create_date
 * @property integer $currency
 * @property string $codeblock
 * @property string $language
 */
class Parsers extends CMyActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'parsers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name,  price_group_1, price_group_2, price_group_3, price_group_4', 'required'),
            array('price_group_1, price_group_2, price_group_3, price_group_4, active_state,  currency', 'numerical', 'integerOnly' => true),
            array('name, parser_class', 'length', 'max' => 127),
            array('supplier_inn, create_date', 'length', 'max' => 20),
            array('supplier', 'length', 'max' => 45),
            array('language', 'length', 'max' => 10),
            array('codeblock', 'safe'),
            array('delivery', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, language, parser_class, price_group_1, price_group_2, price_group_3, price_group_4, active_state, delivery, supplier_inn, supplier, create_date, currency, codeblock', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord)
                $this->create_date = time();
            else {
                if (Yii::app()->user->checkAccess('admin'))
                    $this->SaveBlockCode();
            }
            return true;
        } else
            return false;
    }

    public function SaveBlockCode() {
        $this->parser_class = "ParserSearchModel_$this->id";
        $fkey = fopen(realpath(dirname(__FILE__) . '/../components') . '/' . $this->parser_class . '.php', 'w');
        fwrite($fkey, "<?php class $this->parser_class extends ParserSearchModel { $this->codeblock }");
        fclose($fkey);
    }

    public function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $this->isNewRecord = false;
            $this->save();
        }
    }

    public function afterDelete() {
        @unlink(realpath(dirname(__FILE__) . '/../components') . '/' . $this->parser_class . '.php');
        parent::afterDelete();
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
            'name' => Yii::t('parsers', 'Name'),
            'parser_class' => Yii::t('parsers', 'Class name'),
            'price_group_1' => Yii::t('parsers', 'Price group') . ' 1',
            'price_group_2' => Yii::t('parsers', 'Price group') . ' 2',
            'price_group_3' => Yii::t('parsers', 'Price group') . ' 3',
            'price_group_4' => Yii::t('parsers', 'Price group') . ' 4',
            'active_state' => Yii::t('parsers', 'Active'),
            'delivery' => Yii::t('parsers', 'Delivery date'),
            'supplier_inn' => Yii::t('parsers', 'Supplier INN'),
            'supplier' => Yii::t('parsers', 'Supplier'),
            'create_date' => Yii::t('parsers', 'Creation date'),
            'currency' => Yii::t('parsers', 'currency'),
            'codeblock' => Yii::t('parsers', 'Method code'),
            'language' => Yii::t('languages', 'Language'),
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
        $criteria->compare('parser_class', $this->parser_class, true);
        $criteria->compare('language', $this->language);
        $criteria->compare('price_group_1', $this->price_group_1);
        $criteria->compare('price_group_2', $this->price_group_2);
        $criteria->compare('price_group_3', $this->price_group_3);
        $criteria->compare('price_group_4', $this->price_group_4);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('delivery', $this->delivery);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('currency', $this->currency);
        $criteria->compare('codeblock', $this->codeblock, true);

        $criteria->compare('language', $this->language);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Parsers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getSuppliers() {
        $db = Yii::app()->db;
        $sql = 'SELECT supplier as `supplier`, supplier_inn   as `supplier_inn`  FROM `' . $this->tableName() . '`  ';
        $data = $db->createCommand($sql)->queryAll();
        return $data;
    }

}
