<?php
/**
 * This is the model class for table "crosses_data".
 *
 * The followings are the available columns in table 'crosses_data':
 * @property integer $id
 * @property integer $cross_id
 * @property string $origion_article
 * @property string $origion_brand
 * @property integer $new_state
 */
class CrossesData extends CMyActiveRecord
{

    public $cross_article = '';
    public $cross_brand = '';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'crosses_data';
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            $values = array();
            $this->cross_article = mb_strtoupper($this->cross_article);
            $this->origion_article = mb_strtoupper($this->origion_article);
            $this->cross_article = preg_replace("/[^a-zA-Z0-9]/", "", $this->cross_article);
            $this->origion_article = preg_replace("/[^a-zA-Z0-9]/", "", $this->origion_article);
            $this->cross_brand = mb_strtoupper($this->cross_brand);
            $this->origion_brand = mb_strtoupper($this->origion_brand);

            if (empty($this->partsid)) {
                if (!empty($this->cross_article)) {
                    $db = Yii::app()->db;
                    $find_brand = -1;
                    $find_article = array();
                    
                    $look_for_coincidence = 0;
                    $base = CrossesBase::model()->findByPk($this->base_id);
                    if (is_object($base)) $look_for_coincidence = intval($base->look_for_coincidence);
                    
                    if (($look_for_coincidence == 0) && ($find_brand == -1)) {
                        $cross_rows = $db->createCommand("SELECT SQL_NO_CACHE `origion_article`,`origion_brand`, `partsid` FROM `".$this->tableName()."` WHERE  `origion_article`='$this->origion_article' OR `origion_article`='$this->cross_article' ")->queryAll();
                        if (!empty($this->origion_brand) || !empty($this->cross_brand)) {
                            foreach ($cross_rows as $cross_row) {
                                if ((!empty($this->origion_brand) && $cross_row['origion_article'] == $this->origion_article) || (!empty($this->cross_brand) && $cross_row['origion_article'] == $this->cross_article)) {
                                    $find_brand = $cross_row['partsid'];
                                    $find_article[$cross_row['origion_article']] = $cross_row['origion_article'];
                                }
                            }
                        }
                        if ($find_brand == -1) {
                            foreach ($cross_rows as $cross_row) {
                                if (($cross_row['origion_article'] == $this->origion_article && $cross_row['origion_brand'] == $this->origion_brand) || ($cross_row['origion_article'] == $this->cross_article && $cross_row['origion_brand'] == $this->cross_brand)) {
                                    $find_brand = $cross_row['partsid'];
                                    $find_article[$cross_row['origion_article']] = $cross_row['origion_article'];
                                }
                            }
                        }
                    }
                    
                    if ($find_brand == -1) {
                        $find_brand = $db->createCommand("SELECT SQL_NO_CACHE MAX(`partsid`) FROM `".$this->tableName()."`")->queryScalar();
                        if ($find_brand == NULL || empty($find_brand))
                            $find_brand = 0;
                        $find_brand++;
                        $values[] = "('$this->base_id', '$this->cross_id','$this->origion_article','$this->origion_brand','$find_brand')";
                        $values[] = "('$this->base_id', '$this->cross_id','$this->cross_article','$this->cross_brand','$find_brand')";
                    } else {
                        if (!isset($find_article[$this->origion_article]))
                            $values[] = "('$this->base_id', '$this->cross_id','$this->origion_article','$this->origion_brand','$find_brand')";
                        if (!isset($find_article[$this->cross_article]))
                            $values[] = "('$this->base_id', '$this->cross_id','$this->cross_article','$this->cross_brand','$find_brand')";
                    }
                } else {
                    $this->addError();
                }
            } else {
                $values[] = "('$this->base_id', '$this->cross_id','$this->origion_article','$this->origion_brand','$this->partsid)";
            }
            
            if (count($values) > 0) {
                $db->createCommand('INSERT INTO '.$this->tableName().' (`base_id`, `cross_id`, `origion_article`, `origion_brand`, `partsid`) VALUES '.implode(' , ', $values))->query();

            }
            
            return true;
        }
        
        return false;
    }
    
    public function saveElement()
    {
    	return $this->beforeSave();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cross_id, origion_article', 'required'),
            array('base_id, cross_id, new_state', 'numerical', 'integerOnly' => true),
            array('origion_article, origion_brand, partsid', 'length', 'max' => 127),
            array('cross_article, cross_brand', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, base_id, cross_id, origion_article,  origion_brand, partsid, new_state', 'safe', 'on' => 'search'),
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
        	'base_id' => Yii::t('crosses', 'Cross base'),
            'cross_id' => 'Cross',
            'origion_article' => Yii::t('crosses', 'Original numbe'),
            'cross_article' => Yii::t('crosses', 'Unoriginal number'),
            'origion_brand' => Yii::t('crosses', 'Original brand'),
            'cross_brand' => Yii::t('crosses', 'Unoriginal brand'),
            'patrsid' => Yii::t('crosses', 'partsid'),
        	'crosses_column' => Yii::t('crosses', 'Crosses column'),
            'new_state' => 'New State',
//			'garanty' => 'Гарантированый кросс',
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
        $criteria->compare('base_id', $this->base_id);
        $criteria->compare('cross_id', $this->cross_id);
        $criteria->compare('origion_article', $this->origion_article, true);
        $criteria->compare('origion_brand', $this->origion_brand, true);
        $criteria->compare('partsid', $this->partsid, true);
        $criteria->compare('new_state', $this->new_state);
//		$criteria->compare('garanty',$this->garanty);

        $criteria->order = 'partsid, id';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return CrossesData the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    public function getFormattedCrosses()
    {
    	$html = '';
    
    	$items = CrossesData::model()->findAll(array('condition' => 'partsid='.$this->partsid.' AND id <>'.$this->primaryKey));
    	$count = count($items);
    	for ($i = 0; $i < $count; $i ++) {
    		$html .= '<li>'.$items[$i]->origion_article.($items[$i]->origion_brand ? ' ('.ucfirst($items[$i]->origion_brand).')' : '').'</li>';
    	}
    
    	return $html;
    }
}