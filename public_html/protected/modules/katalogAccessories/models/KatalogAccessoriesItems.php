<?php
/**
 * This is the model class for table "katalog_accessories_items".
 *
 * The followings are the available columns in table 'katalog_accessories_items':
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $title
 * @property string $short_title
 * @property string $text
 * @property string $short_text
 * @property string $slug
 * @property string $image
 * @property integer $cathegory_id
 * @property double $price
 * @property string $supplier
 * @property string $supplier_inn
 * @property integer $active_state
 */
class KatalogAccessoriesItems extends CMyActiveRecord {
    public $_parent_id;
    public $_slug;
    public $cathegory_search;
    public $alias = '';
    public $_image = '';

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
            'short_title' => 'string',
            'short_text' => 'text',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_accessories_items'.(empty($this->load_lang) ? '' : '_'.$this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('slug, title, meta_title, cathegory_id, price', 'required'),
            array('slug', 'checkuniqueslug'),
            array('cathegory_id, active_state', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('meta_title, meta_description, meta_keywords, title, short_title, slug, image, supplier, supplier_inn', 'length', 'max' => 255),
            array('short_text, text', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, meta_title, meta_description, meta_keywords, title, short_title, text, short_text, slug, image, cathegory_id, price, supplier, supplier_inn, active_state', 'safe', 'on' => 'search'),
        );
    }

    public function checkuniqueslug($attribute) {
        $db = Yii::app()->db;
        $sql = 'SELECT id FROM `'.KatalogAccessoriesCathegorias::model()->tableName()."` WHERE slug='$this->slug' and id!='$this->id' LIMIT 1";
        $id = $db->createCommand($sql)->queryScalar();
        if ($id != null && $id != $this->id)
            $this->addError($attribute, Yii::t('languages', 'Use a unique alias'));
        else {
            $sql = 'SELECT id FROM `'.KatalogAccessoriesItems::model()->tableName()."` WHERE slug='$this->slug' and id!='$this->id' LIMIT 1";
            $id = $db->createCommand($sql)->queryScalar();
            if ($id != null && $id != $this->id)
                $this->addError($attribute, Yii::t('languages', 'Use a unique alias'));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'cath' => array(self::HAS_ONE, 'KatalogAccessoriesCathegorias', array('id' => 'cathegory_id')),
        );
    }

    public function afterFind() {
        parent::afterFind();
        if (!empty($this->image))
            $this->_image = $this->image;
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->load_lang == '') {
                if (empty($this->supplier_inn))
                    $this->supplier_inn = Yii::app()->config->get('KatalogAccessories.SupplierInn');
                if (empty($this->supplier))
                    $this->supplier = Yii::app()->config->get('KatalogAccessories.Supplier');
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
            'title' => Yii::t('katalogAccessories', 'Name'),
            'slug' => Yii::t('katalogAccessories', 'Alias'),
            'text' => Yii::t('katalogAccessories', 'Text'),
            'active_state' => Yii::t('katalogAccessories', 'Activity'),
            'short_title' => Yii::t('katalogAccessories', 'Name (short)'),
            'meta_title' => Yii::t('katalogAccessories', 'Meta-header'),
            'meta_description' => Yii::t('katalogAccessories', 'Description page'),
            'meta_keywords' => Yii::t('katalogAccessories', 'Keywords'),
            'short_text' => Yii::t('katalogAccessories', 'Text (short)'),
            'image' => Yii::t('katalogAccessories', 'Picture'),
            'cathegory_id' => Yii::t('katalogAccessories', 'Category'),
            'price' => Yii::t('katalogAccessories', 'Price'),
            'supplier' => Yii::t('katalogAccessories', 'Supplier'),
            'supplier_inn' => Yii::t('katalogAccessories', 'Supplier TIN'),
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
        $criteria->compare('meta_title', $this->meta_title, true);
        $criteria->compare('meta_description', $this->meta_description, true);
        $criteria->compare('meta_keywords', $this->meta_keywords, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('short_title', $this->short_title, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('short_text', $this->short_text, true);
        $criteria->compare('slug', $this->slug, true);
        if ($this->image == 1) {
            $criteria->addCondition("image IS NOT NULL");
        } else if ($this->image == 2)
            $criteria->addCondition("image IS  NULL");


        $criteria->compare('cathegory_id', $this->cathegory_id);
        $criteria->compare('price', $this->price);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('active_state', $this->active_state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogAccessoriesItems the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function behaviors() {
        return array(
            'galleryBehavior' => array(
                'class' => 'GalleryBehavior',
                'idAttribute' => 'gallery_id',
                // 'imagePath' => 'images/katalog/katalogAccessories/'.$this->alias,
                'versions' => array(
                    'small' => array(
                        'centeredpreview' => array(200, 200), //array(98, 98),
                    ),
                    'medium' => array(
                        'resize' => array(800, null),
                    )
                ),
                'name' => true,
                'description' => false,
            ),
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
                    'thumb' => '!200x200',
                )
            ),
        );
    }

    public function getBreadcrumbs() {
        $output = $this->cath->getBreadcrumbs();
        $title = array_pop($output);
        $output[$title] = Yii::app()->urlManager->createUrl('/katalogAccessories/cathegorias/view', array('id' => $this->cath->id));
//        array_push($output, array());
        array_push($output, $this->title);
        return $output;
    }

    public function getImage() {
        foreach ($this->galleryBehavior->getGallery()->galleryPhotos as $photo) {
            if (empty($photo->name))
                $photo->name = $this->title;
            return $photo->getPreview();
            break;
            $items[] = array(
                'id' => $photo->id,
//                'rank' => $photo->rank,
                'title' => (string) $photo->name,
                'image' => $photo->getUrl(),
//                'description' => (string) $photo->description,
                'thumb' => $photo->getPreview(),
            );
        }


        return '/images/nofoto.png';

//        return $image;
    }

    public function getImages() {
        $items = array();
//        $image = $this->getAttachment('thumb');
//
//        if (!empty($image)) {
//            $items[] = array(
//                'id' => $this->id,
////                'rank' => $photo->rank,
//                'title' => (string) $this->title,
//                'image' => '/'.$this->image,
////                'description' => (string) $photo->description,
//                'thumb' => '/'.$image,
//            );
//        }

        foreach ($this->galleryBehavior->getGallery()->galleryPhotos as $photo) {
            if (empty($photo->name))
                $photo->name = $this->title;
            $items[] = array(
                'id' => $photo->id,
//                'rank' => $photo->rank,
                'title' => (string) $photo->name,
                'image' => $photo->getUrl(),
//                'description' => (string) $photo->description,
                'thumb' => $photo->getPreview(),
            );
        }
//        if (empty($image))
//            $image = 'images/nofoto.png';
        return $items;
    }

    public function importTXT($filename, $fileCharset) {
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
			
            $ids = array();
			
            for ($i = 1; $i < $n; $i++) {
                if (strlen($file[$i]) < 5)
                    continue;
                $file[$i] = iconv($fileCharset, 'UTF-8', $file[$i]);
                $string = explode($separator, trim($file[$i]));
                if (count($string) < 8)
                    continue;
                $model = $this->model()->findByPk($string[0]);
                
                if ($model == NULL)
                    $model = new KatalogAccessoriesItems;
                
                if ($model->title != $string[1])
                    $model->title = $string[1];
                
                if ($model->slug != $string[4])
                    $model->slug = $string[4];
                
                if ($model->meta_title != $string[5])
                    $model->meta_title = $string[5];
                
                if ($model->price != $string[2])
                    $model->price = $string[2];
                
                if ($model->supplier != $string[6])
                    $model->supplier = $string[6];
                
                if ($model->supplier_inn != $string[7])
                    $model->supplier_inn = $string[7];
                
                if ($model->active_state != $string[8])
                    $model->active_state = $string[8];
                
                if (empty($string[3])) {
                    continue;
                } else {
                    $temp = KatalogAccessoriesCathegorias::model()->findByAttributes(array('title' => $string[3]));
                    if ($temp != NULL) {
                        $model->cathegory_id = $temp->id;
                    } else
                        continue;
                }

                $temp_i = 1;
                while (!$model->validate()) {
                    $model->slug = $string[4].$temp_i;
                    $temp_i++;
                }
                
                //echo get_class($model).' - '.$model->primaryKey.' - '.$model->title.'<br>';
                
                $model->save();
                
                /**
                 * TODO
                 * save не срабатывает при обновлении
                 * */
                $this->model()->updateByPk($model->primaryKey, array('title' => $model->title, 'slug' => $model->slug, 'meta_title' => $model->meta_title, 'price' => $model->price, 'supplier' => $model->supplier, 'supplier_inn' => $model->supplier_inn, 'active_state' => $model->active_state));
                
                //echo get_class($model).' - '.$model->primaryKey.' - '.$model->title.'<br>';
                
                //$m = KatalogAccessoriesItems::model()->findByPk($model->primaryKey);
                //echo $m->title;
                
                //exit;
                
                $ids[] = 'id!='.$model->id;
            }

            $data = $db->createCommand('DELETE FROM  '.$this->tableName().' WHERE '.implode(' AND ', $ids))->query();
        }
    }

    public function export() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT *, (SELECT title FROM '.KatalogAccessoriesCathegorias::model()->tableName().' WHERE id=t.cathegory_id) AS cathegory_title FROM '.$this->tableName().' `t`')->queryAll();
        $export = 'Id;Название;Цена;Название категории;Псевдоним;Мета-заголовок;Поставщик;Поставщик ИНН;Активность'."\n";
        foreach ($data as $value) {
            $export .= $value['id'].';'.$value['title'].';'.$value['price'].';'.$value['cathegory_title'].';'.$value['slug'] .
                    ';'.$value['meta_title'].';'.$value['supplier'].';'.$value['supplier_inn'].';'.$value['active_state']."\n";
        }
        return iconv('UTF-8', 'cp1251', $export);
    }
}