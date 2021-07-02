<?php
/**
 * This is the model class for table "katalog_vavto_items".
 *
 * The followings are the available columns in table 'katalog_vavto_items':
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $title
 * @property string $slug
 * @property integer $cathegory_id
 * @property double $price
 * @property string $supplier
 * @property string $supplier_inn
 * @property integer $active_state
 * @property string $detail_type 
 *
 * The followings are the available model relations:
 * @property KatalogVavtoCathegorias $cathegory
 */
class KatalogVavtoItems extends CMyActiveRecord {
    public $_parent_id;
    public $_slug;
    public $cathegory_search;
    public $alias = '';
    public $_image = '';
    public $search_text = '';

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
            'sub_title' => 'string',
            'short_text' => 'text',
            'detail_type' => 'string',
//            '' => 'string',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_vavto_items'.(empty($this->load_lang) ? '' : '_'.$this->load_lang);
    }

    public function init() {
        parent::init();
        $this->active_state = 1;
    }

    public function afterFind() {
        parent::afterFind();
        if (!empty($this->image)) {
            $this->_image = $this->image;
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('slug, title, meta_title, cathegory_id', 'required'),
            array('slug', 'checkuniqueslug'),
            array('cathegory_id, active_state, in_stock', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('text,detail_type,short_text', 'safe'),
            array('meta_title, meta_description, meta_keywords, title,  slug, article, supplier, supplier_inn,sub_title,image', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, search_text, meta_title, meta_description, meta_keywords, title,  slug,cathegory_id, price, supplier, supplier_inn, active_state, in_stock', 'safe', 'on' => 'search'),
        );
    }

    public function checkuniqueslug($attribute) {
        $db = Yii::app()->db;
        $sql = 'SELECT id FROM `'.KatalogVavtoCathegorias::model()->tableName()."` WHERE slug='$this->slug' and id!='$this->id' LIMIT 1";
        $id = $db->createCommand($sql)->queryScalar();
        if ($id != null && $id != $this->id)
            $this->addError($attribute, Yii::t('katalogVavto', 'Use a unique Alias'));
        else {
            $sql = 'SELECT id FROM `'.KatalogVavtoItems::model()->tableName()."` WHERE slug='$this->slug' and id!='$this->id' LIMIT 1";
            $id = $db->createCommand($sql)->queryScalar();
            if ($id != null && $id != $this->id)
                $this->addError($attribute, Yii::t('katalogVavto', 'Use a unique Alias'));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'cath' => array(self::BELONGS_TO, 'KatalogVavtoCars', 'cathegory_id'),
        );
    }

    public function behaviors() {
        return array(
            'image' => array(
                'class' => 'ext.AttachmentBehavior.AttachmentBehavior',
                # Should be a DB field to store path/filename
                'attribute' => 'image',
                # Default image to return if no image path is found in the DB
                //'fallback_image' => 'images/sample_image.gif',
                'path' => "images/:model/:id.:ext",
//                'processors' => array(
//                    array(
//                        # Currently GD Image Processor and Imagick Supported
//                        'class' => 'ImagickProcessor',
//                        'method' => 'resize',
//                        'params' => array(
//                            'width' => 310,
//                            'height' => 150,
//                            'keepratio' => true,
//                        )
//                    )
//                ),
                'styles' => array(
                    # name => size 
                    # use ! if you would like 'keepratio' => false
                    'thumb' => '!50x25',
                )
            ),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->load_lang == '') {
                if (empty($this->supplier_inn))
                    $this->supplier_inn = Yii::app()->config->get('KatalogVavto.SupplierInn');
                if (empty($this->supplier))
                    $this->supplier = Yii::app()->config->get('KatalogVavto.Supplier');

                if (empty($this->image) && !empty($this->_image))
                    $this->image = $this->_image;
            }
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
            'title' => Yii::t('katalogVavto', 'Name'),
            'article' => Yii::t('katalogVavto', 'Article'),
            'meta_title' => Yii::t('katalogVavto', 'Title'),
            'meta_description' => Yii::t('katalogVavto', 'page Description'),
            'meta_keywords' => Yii::t('katalogVavto', 'Keywords'),
            'cathegory_id' => Yii::t('katalogVavto', 'Category'),
            'price' => Yii::t('katalogVavto', 'Cost'),
            'supplier' => Yii::t('katalogVavto', 'Supplier'),
            'supplier_inn' => Yii::t('katalogVavto', 'Supplier INN'),
            'active_state' => Yii::t('katalogVavto', 'Enable'),
            'image' => Yii::t('katalogVavto', 'Picture'),
            '_image' => Yii::t('katalogVavto', 'Picture'),
            'sub_title' => Yii::t('katalogVavto', 'Subtitled'),
            'slug' => Yii::t('katalogVavto', 'Alias'),
            'text' => Yii::t('katalogVavto', 'Description'),
            'short_text' => Yii::t('katalogVavto', 'Text for alt'),
            'detail_type' => Yii::t('katalogVavto', 'Items Type'),
        	'in_stock' => Yii::t('katalogVavto', 'In stock'),
        );
    }

    public function getCarPartTypes() {
        return array(
            'char1' => Yii::t('katalogVavto', 'Engine'),
            'char2' => Yii::t('katalogVavto', 'Transmission'),
            'char3' => Yii::t('katalogVavto', 'Suspension'),
            'char4' => Yii::t('katalogVavto', 'Electrics'),
            'char5' => Yii::t('katalogVavto', 'Parts Service'),
            'char6' => Yii::t('katalogVavto', 'Parts Body'),
            'char7' => Yii::t('katalogVavto', 'Beauty'),
        );
    }

    public function getCarPartType() {
        $ar = $this->getCarPartTypes();
        if (isset($ar[$this->detail_type])) {
            return $ar[$this->detail_type];
        }
        return '';
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
        if (!empty($this->search_text)) {
//            $criteria->compare('title', $this->search_text, true, 'OR');
//            $criteria->compare('text', $this->search_text, true, 'OR');
            $values = explode(' ', $this->search_text);
            foreach ($values as $v) {
                $v = trim($v);
                if (!empty($v)) {
                    $criteria->compare('title', $v, true, 'OR');
                    $criteria->compare('text', $v, true, 'OR');
                    $criteria->compare('title', $v, true, 'OR');
                    $criteria->compare('article', $v, true, 'OR');
                }
            }
        } else {
            $criteria->compare('id', $this->id);
            $criteria->compare('meta_title', $this->meta_title, true);
            $criteria->compare('meta_description', $this->meta_description, true);
            $criteria->compare('meta_keywords', $this->meta_keywords, true);
            $criteria->compare('title', $this->title, true);
            $criteria->compare('slug', $this->slug, true);
            $criteria->compare('cathegory_id', $this->cathegory_id);
            $criteria->compare('price', $this->price);
            $criteria->compare('supplier', $this->supplier, true);
            $criteria->compare('supplier_inn', $this->supplier_inn, true);
            $criteria->compare('active_state', $this->active_state);
            $criteria->compare('in_stock', $this->in_stock, true);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getItemsDataProvider() {
        $model = new KatalogVavtoItems('search');
        $criteria = new CDbCriteria;
        $criteria->compare('cathegory_id', $this->cathegory_id);
        $type = Yii::app()->request->getParam('type', '');
        if (!empty($type))
            $criteria->compare('detail_type', $type);

        $criteria->compare('active_state', 1);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogVavtoItems the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getBreadcrumbs() {
        $output = $this->cath->getBreadcrumbs();
        
        //Do category link
        if (is_array($output) && array_key_exists(0, $output)) {
        	$name = $output[0];
        	 
        	unset($output[0]);
        	 
        	$output[$name] = array('/katalogVavto/cars/view', 'id' => $this->cath->primaryKey);
        }
        
        //array_pop($output);
        array_push($output, $this->title);
        return $output;
    }

    public function importTXT($filename, $fileCharset, &$tmp_echo) {
		//$tmp_echo = '';
        ob_start();
        $file = file($filename);

        $n = count($file);
        $values = '';

        $db = Yii::app()->db;
        $z = 0;
        if ($n > 1) {
            $separator = ";";
            $string = explode($separator, trim($file[0]));
            if (count($string) < 8)
                $separator = "\t";

            for ($i = 1; $i < $n; $i++) {
                if (strlen($file[$i]) < 5)
                    continue;
                $file[$i] = iconv($fileCharset, 'UTF-8', $file[$i]);
                $string = explode($separator, trim($file[$i]));
                if (count($string) < 8) continue;
                
                $model = null;
                if (intval($string[0]))
                	$model = $this->model()->findByPk($string[0]);
                if ($model == null)
                    $model = new KatalogVavtoItems;
                
                $model->article = $string[1];
                if ($model->title != $string[2])
                    $model->title = $string[2];
                $model->sub_title = $string[3];
				//if ($model->slug != $string[6])
                $model->slug = $string[6];
				//if ($model->meta_title != $string[6])
                $model->meta_title = $string[7];
                $model->meta_description = $string[8];
                $model->meta_keywords = $string[9];
				//if ($model->price != $string[3])
				//$model->price = $string[3];
                $model->detail_type = $string[5];
				//if ($model->supplier != $string[7])
				//$model->supplier = $string[10];
				//if ($model->supplier_inn != $string[8])
				//$model->supplier_inn = $string[11];
				//if ($model->active_state != $string[9])
                if (array_key_exists(10, $string)) $model->active_state = $string[10];
                if (array_key_exists(11, $string)) $model->in_stock = $string[11];
                
                if (empty($model->meta_title))
                    $model->meta_title = $model->title;
                $temp_i = 1;
                if (empty($string[6]))
                    $string[6] = CMyTranslit::getText($model->title);

                if (empty($model->slug))
                    $model->slug = $string[6];

				//$model->slug=  str_replace("'", "\'", $model->slug);

                if (empty($string[4])) {
                    continue;
                } else {
                    $temp = KatalogVavtoCars::model()->findByAttributes(array('title' => $string[4]));
                    if ($temp != NULL) {
                        $model->cathegory_id = $temp->id;
                    } else {
                        echo Yii::t('katalogVavto', 'No car')." $string[4] ".Yii::t('katalogVavto', 'in line')." $i";
                        echo '<br>';
						//flush();
                        continue;
                    }
                }

                $temp_i = 1;
                while (!$model->validate()) {
					//print_r($model->errors);
					//print_r($model);
					//die;
                    $model->slug = $string[6].$temp_i;
                    $temp_i++;
                }
                
                $model->save();
                
                /**
                 * TODO
                 * save не срабатывает при обновлении
                 * */
                $this->model()->updateByPk($model->primaryKey, array('article' => $model->article, 'title' => $model->title, 'sub_title' => $model->sub_title, 'slug' => $model->slug, 'meta_title' => $model->meta_title, 'meta_description' => $model->meta_description, 'meta_keywords' => $model->meta_keywords, 'detail_type' => $model->detail_type, 'active_state' => $model->active_state));
                
                $ids[] = 'id!='.$model->id;
            }
            //$data = $db->createCommand('DELETE FROM  '.$this->tableName().'  WHERE '.implode(' and ', $ids))->query();
        }
        
        $tmp_echo = ob_get_clean();
    }

    public function export() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT *, (SELECT title FROM '.KatalogVavtoCars::model()->tableName().' WHERE id=t.cathegory_id) AS cathegory_title  FROM '.$this->tableName().' `t`')->queryAll();
        $export = 'Id;'.
        		  Yii::t('katalogVavto', 'Article').';'.
        		  Yii::t('katalogVavto', 'Name').';'.
        		  Yii::t('katalogVavto', 'Subtitled').';'.
        		  Yii::t('katalogVavto', 'Category').';'.
        		  Yii::t('katalogVavto', 'Items Type').';'.
        		  Yii::t('katalogVavto', 'Alias').';'.
        		  Yii::t('katalogVavto', 'Title').';'.
        		  Yii::t('katalogVavto', 'Description').';'.
        		  Yii::t('katalogVavto', 'Keywords').';'.
        		  Yii::t('katalogVavto', 'Enable').';'.
        		  Yii::t('katalogVavto', 'In stock')."\n";
        
        foreach ($data as $value) {
            $export .= $value['id'].';'.
            		   $value['article'].';'.
            		   $value['title'].';'.
            		   $value['sub_title'].';'.
            		   $value['cathegory_title'].';'.
            		   $value['detail_type'].';'.
            		   $value['slug'].';'.
            		   $value['meta_title'].';'.
            		   $value['meta_description'].';'.
            		   $value['meta_keywords'].';'.
            		   $value['active_state'].';'.
            		   $value['in_stock']."\n";
        }
        return iconv('UTF-8', 'cp1251', $export);
    }
}