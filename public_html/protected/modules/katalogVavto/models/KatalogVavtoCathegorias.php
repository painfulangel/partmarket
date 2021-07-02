<?php
/**
 * This is the model class for table "katalog_vavto_cathegorias".
 *
 * The followings are the available columns in table 'katalog_vavto_cathegorias':
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property string $level
 * @property string $parent_id
 * @property integer $order
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property integer $active_state
 * @property string $image
 * @property string $short_title 
 * @property string $menu_image 
 * @property string $short_text
 * @property string $sub_image_class 
 * @property string $years
 * @property string $index_image 
 *
 * The followings are the available model relations:
 * @property KatalogVavtoItems[] $katalogVavtoItems
 */
class KatalogVavtoCathegorias extends CMyNetActiveRecord {
    public $_image = '';
    public $_index_image = '';
    public $_parent_id;
    public $_slug;

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
            'short_title' => 'string',
            'short_text' => 'text',
            'years' => 'string',
//            ''=>'string',
        );
    }

    public function init() {
        parent::init();
        $this->active_state = 1;
    }

    public function getAllChilds($childs_id, $limit = 0) {
        $criteria = new CDbCriteria;
        if ($childs_id == null) {
            $childs = $this->children()->findAll('active_state=1 AND (SELECT COUNT(*) FROM `'.$this->tableName().'` `tch` WHERE `tch`.parent_id=t.id LIMIT 1)>0');
            $childs_id = array();
            foreach ($childs as $child) {
                $childs_id[] = $child->id;
            }
        }
        $temp = array(0);
        foreach ($childs_id as $value) {
            $temp[] = " parent_id='$value' ";
        }

        $criteria->condition = implode(' OR ', $temp);
        if ($limit > 0) {
//            $criteria->limit = "$limit";
            $criteria->order = " RAND() ";
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $limit,
                ),
            ));
        }



        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_vavto_cathegorias'.(empty($this->load_lang) ? '' : '_'.$this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('slug, title, meta_title,short_title', 'required'),
//            array('title', 'unique'),
//            array('slug', 'checkuniqueslug'),
//            array('meta_title, meta_description, meta_keywords, root, lft, rgt, level, parent_id', 'required'),
            array('order', 'numerical', 'integerOnly' => true),
            array('meta_title, meta_description, meta_keywords, title, slug, short_title,menu_image,sub_image_class, years', 'length', 'max' => 255),
            array('root, lft, rgt, level, parent_id', 'length', 'max' => 10),
            array('text,active_state, image, short_text,index_image,image,_index_image,_image', 'safe'),
            array('_index_image', 'file', 'allowEmpty' => true, 'types' => 'gif, jpg, png', 'maxSize' => 2621440,),
//            array('_image', 'file', 'allowEmpty' => true, 'types' => 'jpg, gif, png', 'maxSize' => 2 * 1024 * 1024),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, meta_title, active_state, meta_description, meta_keywords, root, short_title, lft, rgt, level, parent_id, order, title, slug, text', 'safe', 'on' => 'search'),
        );
    }

    public function checkuniqueslug($attribute) {
        $db = Yii::app()->db;
        $sql = 'SELECT id FROM `'.KatalogVavtoCathegorias::model()->tableName()."` WHERE slug='$this->slug'  LIMIT 1";
        $id = $db->createCommand($sql)->queryScalar();
        if ($id != null && $id != $this->id)
            $this->addError($attribute, Yii::t('katalogVavto', 'Use a unique Alias'));
        else {
            $sql = 'SELECT id FROM `'.KatalogVavtoItems::model()->tableName()."` WHERE slug='$this->slug' LIMIT 1";
            $id = $db->createCommand($sql)->queryScalar();
            if ($id != null && $id != $this->id)
                $this->addError($attribute, Yii::t('katalogVavto', 'Use a unique Alias'));
        }
    }

    public function beforeSave() {

        if (parent::beforeSave()) {
            if ($this->load_lang == '') {
                if ($this->isNewRecord) {

                    $this->order = Yii::app()->db->createCommand("SELECT {$this->tableName()}.order FROM `{$this->tableName()}` ORDER BY `order` DESC LIMIT 1")->queryScalar() + 1;
                }
                if (empty($this->image) && !empty($this->_image))
                    $this->image = $this->_image;

                $this->_index_image = CUploadedFile::getInstance($this, '_index_image');
                if ($this->_index_image != NULL) {
                    $filename = pathinfo($this->_index_image->getName());
                    $extension = $filename['extension'];
                    $filename = md5(time().$this->title).'.'.$extension;
                    $this->_index_image->saveAs($this->getAbsolutePathImage().$filename);

                    @unlink($this->getAbsolutePathImage().$this->index_image);

                    $this->index_image = $filename;
                }
            }
            return true;
        }
        return false;
    }

    public function getImage($type = '', $just_name = false) {
        if (empty($type)) {
            $image = $this->getAttachment('thumb');
            if (empty($image))
                $image = 'images/nofoto.png';
            return $image;
        }else {
            if (!$just_name) {
                if (!empty($this->index_image) && file_exists($this->getAbsolutePathImage().$this->index_image))
                    return 'images/VavtoCath/'.$this->index_image;
                else
                    return '';
            }else {
                if (!empty($this->index_image) && file_exists($this->getAbsolutePathImage().$this->image))
                    return $type.$this->index_image;
                else
                    return '';
            }
        }
    }

    public function getAbsolutePathImage() {
        return realpath(Yii::app()->basePath.'/..'.'/images/VavtoCath/').'/';
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'katalogVavtoItems' => array(self::HAS_MANY, 'KatalogVavtoItems', 'cathegory_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'meta_title' => Yii::t('katalogVavto', 'Title'),
            'meta_description' => Yii::t('katalogVavto', 'page Description'),
            'meta_keywords' => Yii::t('katalogVavto', 'Keywords'),
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'parent_id' => Yii::t('katalogVavto', 'Parent'),
            'order' => 'Order',
            'title' => Yii::t('katalogVavto', 'Name'),
            'short_title' => Yii::t('katalogVavto', 'Short title'),
            'slug' => Yii::t('katalogVavto', 'Alias'),
            'text' => Yii::t('katalogVavto', 'Text'),
            'short_text' => Yii::t('katalogVavto', 'Text for alt'),
            'active_state' => Yii::t('katalogVavto', 'Enable'),
            'image' => Yii::t('katalogVavto', 'Picture'),
            '_image' => Yii::t('katalogVavto', 'Picture'),
            'index_image' => Yii::t('katalogVavto', 'The picture (on the main car)'),
            '_index_image' => Yii::t('katalogVavto', 'The picture (on the main car)'),
            'menu_image' => Yii::t('katalogVavto', 'Left menu style'),
            'sub_image_class' => Yii::t('katalogVavto', 'Sub style'),
            'years' => Yii::t('katalogVavto', 'Year'),
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
        $criteria->compare('root', $this->root, true);
        $criteria->compare('lft', $this->lft, true);
        $criteria->compare('rgt', $this->rgt, true);
        $criteria->compare('level', $this->level, true);
        $criteria->compare('parent_id', $this->parent_id, true);
        $criteria->compare('order', $this->order);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('short_title', $this->short_title, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('active_state', $this->active_state);
//        $criteria->compare('image', $this->image, true);
        if ($this->image == 1) {
            $criteria->addCondition("image IS NOT NULL");
        } else if ($this->image == 2)
            $criteria->addCondition("image IS  NULL");

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 99999,
            ),
        ));
    }

    public function defaultScope() {
        return array(
            'order' => 'root, lft',
        );
    }

    public function scopes() {
        return array(
            'published' => array(
                'condition' => 'active_state = 1',
            ),
        );
    }

    public function behaviors() {
        return array(
            'tree' => array(
                'class' => 'ext.nested-set.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'rootAttribute' => 'root',
                'hasManyRoots' => true,
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
                    'thumb' => '!50x25',
                )
            ),
//            'index_image' => array(
//                'class' => 'ext.AttachmentBehavior.AttachmentBehavior',
//                # Should be a DB field to store path/filename
//                'attribute' => 'index_image',
//                # Default image to return if no image path is found in the DB
//                //'fallback_image' => 'images/sample_image.gif',
//                'path' => "images/:model/index_image:id.:ext",
////                'processors' => array(
////                    array(
////                        # Currently GD Image Processor and Imagick Supported
////                        'class' => 'ImagickProcessor',
////                        'method' => 'resize',
////                        'params' => array(
////                            'width' => 310,
////                            'height' => 150,
////                            'keepratio' => true,
////                        )
////                    )
////                ),
//                'styles' => array(
//                    # name => size 
//                    # use ! if you would like 'keepratio' => false
//                    'thumb' => '!50x25',
//                )
//            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogVavtoCathegorias the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function afterFind() {
        parent::afterFind();

        $this->_parent_id = $this->parent_id;
        $this->_slug = $this->slug;

        if (!empty($this->image))
            $this->_image = $this->image;
        if (!empty($this->index_image))
            $this->_index_image = $this->index_image;
    }

    public function afterSave() {
        parent::afterSave();

        if ($this->parent_id !== $this->_parent_id || $this->slug !== $this->_slug)
            Yii::app()->controller->module->updatePathsMap();
    }

    public function afterUpdateOrder() {
        Yii::app()->controller->module->updatePathsMap();
    }

    protected function afterDelete() {
        parent::afterDelete();

        Yii::app()->controller->module->updatePathsMap();
    }

    public function getBreadcrumbs() {
        $ancestors = $this->ancestors()->findAll();
        $output = array();
        foreach ($ancestors as $ancestor)
            $output[$ancestor->title] = Yii::app()->urlManager->createUrl('/katalogVavto/cathegorias/view', array('id' => $ancestor->id));
        array_push($output, $this->title);
        return $output;
    }

    public function selectList() {
        $output = array();
        $nodes = $this->findAll();
        foreach ($nodes as $node)
            $output[$node->id] = str_repeat('  ', $node->level - 1).$node->title;
        return $output;
    }

    public function moveItems($id) {
        //$db = Yii::app()->db;
        //$sql = 'UPDATE `'.KatalogVavtoItems::model()->tableName().'` SET  '." cathegory_id='$id' WHERE cathegory_id='$this->id' ";
        //$db->createCommand($sql)->query();
    	$command = Yii::app()->db->createCommand();
    	$command->update(KatalogVavtoItems::model()->tableName(), array('cathegory_id' => $id), 'cathegory_id=:cathegory_id', array(':cathegory_id' => $this->id));
    }

    public function InitOrderFunc() {
        $this->slug = 'temp'.time();
        $this->title = 'temp';
        $this->meta_title = 'temp';
    }

    public function getItemsDataProvider() {
        $criteria = new CDbCriteria;

        if (Yii::app()->request->getParam('id', '0') == $this->id) {
            $ids = array('cathegory_id='.$this->id);
            $ancestors = $this->children()->findAll('active_state=1 ');
            foreach ($ancestors as $ancestor) {
                $ids[] = 'cathegory_id='.$ancestor->id;
            }
            $criteria->addCondition(implode($ids, ' OR '));
        } else {
            $criteria->compare('cathegory_id', Yii::app()->request->getParam('id', '0'));
        }
        $criteria->compare('active_state', 1);


        return new CActiveDataProvider('KatalogVavtoItems', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
    }


    public function importTXT($filename, $fileCharset) {
        set_time_limit(0);
        $file = file($filename);

        $n = count($file);
        $values = '';

        $db = Yii::app()->db;
        $z = 0;
        $ids = array();
        $criteria = new CDbCriteria;

        $level2 = array(
            'char1' => Yii::t('katalogVavto', 'Sedans and coupes'),
            'char2' => Yii::t('katalogVavto', 'Hatchbacks and Wagons'),
            'char3' => Yii::t('katalogVavto', 'Crossovers and SUVs'),
            'char4' => Yii::t('katalogVavto', 'Commercial car'),
        );
        $level4 = array(
            'char1' => Yii::t('katalogVavto', 'Engine'),
            'char2' => Yii::t('katalogVavto', 'Transmission'),
            'char3' => Yii::t('katalogVavto', 'Suspension'),
            'char4' => Yii::t('katalogVavto', 'Electrics'),
            'char5' => Yii::t('katalogVavto', 'Parts Service'),
            'char6' => Yii::t('katalogVavto', 'Parts Body'),
            'char7' => Yii::t('katalogVavto', 'Beauty'),
        );
        $level4 = array_flip($level4);
        $level2 = array_flip($level2);
        if ($n > 1) {
            $separator = ";";
            $string = explode($separator, trim($file[0]));
            if (count($string) < 5)
                $separator = "\t";
            for ($i = 1; $i < $n; $i++) {
                if (strlen($file[$i]) < 5)
                    continue;
                $file[$i] = iconv($fileCharset, 'UTF-8', $file[$i]);
                $string = explode($separator, trim($file[$i]));
                if (count($string) < 5)
                    continue;
                $model = $this->model()->findByPk($string[0]);
                foreach ($string as $key => $value) {
                    $string[$key] = trim($value);
                }
                if ($model == NULL)
                    $model = new KatalogVavtoCathegorias;
                $model->short_title = $string[1];
                $model->title = $string[2];
                $model->slug = $string[5];
                $model->meta_title = $string[6];
                $model->meta_description = $string[7];
                $model->meta_keywords = $string[8];
//                $model->short_text = $string[9];
//                $model->text = $string[10];
                if (empty($model->meta_title))
                    $model->meta_title = $model->title;
                if (empty($model->meta_title))
                    $model->meta_title = $model->title;
                if ($string[3] == '0') {
                    $model->parent_id = 0;
                } else {
                    $parent_parent_title = $string[4];
                    $cond = '';
                    if ($parent_parent_title != '0') {
                        $cond = ' ( SELECT count(*) FROM '.$this->tableName()." WHERE title='$parent_parent_title' AND id=t.parent_id LIMIT 1)>0";
                    }
//                    if ($i > 500)
//                        die;

                    $critieria->condition = $cond;
                    $temp = $this->model()->findByAttributes(array('title' => $string[3]), $cond);

                    if ($temp != NULL) {
                        $model->parent_id = $temp->id;
                    } else
                        $model->parent_id = 0;
                }
                $temp_i = 1;


                if (empty($string[5]))
                    $string[5] = CMyTranslit::getText($model->title);

                if (empty($model->slug))
                    $model->slug = $string[5];

                while (!$model->validate()) {
//                    echo $model->slug.'<br>';
//                    print_r($model->errors);
                    $model->slug = $string[5].$temp_i;
                    $temp_i++;
                }

//                print_r($model);
//                $model->validate();
//                print_r($model->errors);
//                die;
                if ($model->validate()) {
                    if ($model->parent_id != 0) {
                        $parent = KatalogVavtoCathegorias::model()->findByPk($model->parent_id);
                        if ($parent !== null)
                            $model->appendTo($parent);
                    }
                    $model->saveNode();
                    if ($model->level == 2) {
                        if (empty($model->sub_image_class) && isset($level2[$model->short_title])) {
                            $model->sub_image_class = $level2[$model->short_title];
                            $model->saveNode();
                        }
                    }
                    if ($model->level == 4) {
                        if (empty($model->sub_image_class) && isset($level4[$model->short_title])) {
                            $model->sub_image_class = $level4[$model->short_title];
                            $model->saveNode();
                        }
                    }
                }
//                print_r($model->errors);
//                die;
                echo $model->id.'<br>';
                $ids[] = 'id!='.$model->id;
            }
            $data = $db->createCommand('DELETE FROM  '.$this->tableName().'  WHERE '.implode(' AND ', $ids))->query();
        }
    }

    public function export() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT *, (SELECT title FROM '.$this->tableName().' WHERE id=t.parent_id LIMIT 1) AS parent_title, (SELECT IF(parent_id != \'0\',(SELECT IF(parent_id != \'0\',`title`,0) FROM '.$this->tableName().' WHERE id=t_parent.parent_id LIMIT 1),0) FROM '.$this->tableName().' `t_parent` WHERE id=t.parent_id LIMIT 1) AS parent_parent_title FROM '.$this->tableName().' `t`')->queryAll();
        $export = 'Id;Короткое название;Название;Название родительськой категории (0 если родителя нету);Название родительськой категории родителя (0 если родителя родителя не существует);Псевдоним;Мета-заголовок;Мета-описание;Мета-слова'."\n";
        foreach ($data as $value) {
            $export .= $value['id'].';'.$value['short_title'].';'.$value['title'].';'.($value['parent_id'] != 0 ? $value['parent_title'] : '0').';'.($value['parent_parent_title'] != 0 ? $value['parent_parent_title'] : '0').';'.$value['slug'].';'.$value['meta_title'].';'.$value['meta_description'].';'.$value['meta_keywords']."\n"; //.';'.$value['short_text'].';'.$value['text']. "\n";
        }

        return iconv('UTF-8', 'cp1251', $export);
    }
}