<?php
/**
 * This is the model class for table "languages".
 *
 * The followings are the available columns in table 'languages':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $link_name
 */
class Languages extends CActiveRecord {
    public $rewrite_files = false;
    public $upload_files = null;
    public $models_to_update = array(
        'Config' => 'ext.config.models',
        'Menus' => 'ext.menu.models',
        'Page' => 'ext.pages.models',
//        'Page' => 'ext.pages_left.models',
        'Currencies' => 'currencies.models',
        'KatalogAccessoriesCathegorias' => 'katalogAccessories.models',
        'KatalogAccessoriesItems' => 'katalogAccessories.models',
        'KatalogVavtoBrands' => 'katalogVavto.models',
        'KatalogVavtoCars' => 'katalogVavto.models',
        'KatalogVavtoCathegorias' => 'katalogVavto.models',
        'KatalogVavtoItems' => 'katalogVavto.models',
        'News' => 'news.models',
        'WebPaymentsSystem' => 'webPayments.models',
//                  '',  
//                  '',  
//                  '',  
    );

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'languages';
    }

    public function copyr($source, $dest) {
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
            $company = ($_POST['company']);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            if ($dest !== "$source/$entry") {
                $this->copyr("$source/$entry", "$dest/$entry");
            }
        }

        // Clean up
        $dir->close();
        return true;
    }

    public function getContents($dir, $files = array()) {
        if (!($res = opendir($dir)))
            return array();
        while (($file = readdir($res)) == TRUE)
//                echo $file;
            if ($file != "." && $file != "..") {
                if (is_dir("$dir/$file")) {
//                    array_push($files, "$dir/$file");
                    $files = $this->getContents("$dir/$file", $files);
                } else
                    array_push($files, "$dir/$file");
            }
        closedir($res);
        return $files;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            print_r($objs);
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            foreach ($this->models_to_update as $name => $path) {
                Yii::import($path . '.' . $name);
                $model = new $name;
                $model->createTranslatedModel($this->link_name);
            }if ($this->rewrite_files || !file_exists(Yii::app()->basePath . '/messages/' . $this->link_name))
                $this->copyr(Yii::app()->basePath . '/messages/en', Yii::app()->basePath . '/messages/' . $this->link_name);
        }else {
            $this->upload_files = CUploadedFile::getInstance($this, 'upload_files');
            if ($this->upload_files != NULL) {
                $zip_name = Yii::app()->basePath . '/runtime/translate_zip/' . $this->upload_files;
                $this->upload_files->saveAs($zip_name);
                $zip = new ZipArchive();
                if ($zip->open($zip_name) === TRUE) {
                    $zip_folder = Yii::app()->basePath . '/runtime/translate_zip/' . md5($zip_name . time());
                    $zip->extractTo($zip_folder);
                    $zip->close();
                    $messages_dir = Yii::app()->basePath . '/messages/' . $this->link_name;
                    if (!file_exists($messages_dir))
                        mkdir($messages_dir);
                    $files_to_copy = $this->getContents($zip_folder);
                    foreach ($files_to_copy as $file) {
                        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php') {
                            copy($file, $messages_dir . '/' . basename($file));
                        }
                    }
                    $this->removeDirectory($zip_folder);
                }
                unlink($zip_name);
            }
        }
    }

    public function afterDelete() {
        parent::afterDelete();
        foreach ($this->models_to_update as $name => $path) {
            Yii::import($path . '.' . $name);
            $model = new $name;
            $model->deleteTranslatedModel($this->link_name);
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, short_name', 'length', 'max' => 127),
            array('link_name', 'length', 'max' => 10),
            array('rewrite_files', 'safe'),
            array('upload_files', 'file', 'types' => 'zip', 'allowEmpty' => true, 'maxSize' => 2097152),
//            array('upload_files', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, short_name, link_name, active', 'safe', 'on' => 'search'),
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
            'name' => Yii::t('languages', 'Name'),
            'short_name' => Yii::t('languages', 'Short title'),
            'link_name' => Yii::t('languages', 'Prefix'),
            'rewrite_files' => Yii::t('languages', 'Rewrite files'),
        	'active' => Yii::t('languages', 'Active'),
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
        $criteria->compare('short_name', $this->short_name, true);
        $criteria->compare('link_name', $this->link_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Languages the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}