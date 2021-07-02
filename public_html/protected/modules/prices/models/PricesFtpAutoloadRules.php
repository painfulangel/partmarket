<?php

/**
 * This is the model class for table "prices_ftp_autoload_rules".
 *
 * The followings are the available columns in table 'prices_ftp_autoload_rules':
 * @property integer $id
 * @property string $ftp_server
 * @property string $ftp_login
 * @property string $ftp_password
 * @property integer $ftp_auth_type
 * @property string $ftp_destination_folder
 * @property string $search_file_criteria
 * @property string $delivery
 * @property integer $active_state
 * @property integer $store_id
 * @property string $load_period_days
 * @property string $load_period_hours
 * @property string $load_period_minutes
 * @property integer $start_line
 * @property integer $finish_line
 * @property string $brand
 * @property string $name
 * @property string $price
 * @property string $multiply
 * @property string $replace_multiply
 * @property string $quantum
 * @property string $replace_delivery
 * @property string $replace_article
 * @property string $replace_brand
 * @property string $replace_name
 * @property string $replace_price
 * @property string $replace_quantum
 * @property string $article
 * @property integer $delete_state
 * @property strint $charset 
 * @property string $xml_element_tag 
 * @property string $cron_general   
 * @property string $method_type 
 * @property string $mail_subject
 * @property string $mail_body
 * @property string $mail_from
 * @property string $mail_file
 * @property integer $send_admin_mail
 * @property integer $mail_id
 * @property integer $download_time
 * @property integer $download_count
 * @property string $remote_url
 * 
 */
class PricesFtpAutoloadRules extends CMyActiveRecord {

    public $_start_line = 0;
    public $_finish_line = 0;
    public $filename = '';
    public $first_delete = false;
    public $_ftp_password;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_ftp_autoload_rules';
    }

    public function afterFind() {
        parent::afterFind();
        $this->_ftp_password = $this->ftp_password;
        $this->ftp_password = '';
    }

    public function beforeSave() {
        if (empty($this->ftp_destination_folder)) {
            $this->ftp_destination_folder = '/';
        }
        if (!$this->isNewRecord) {
            if (empty($this->ftp_password)) {
                $this->ftp_password = $this->_ftp_password;
            }
        }
        if (parent::beforeSave()) {
            $this->delete_state = 1;
            $minutes = array(
                '1' => '*/05',
                '2' => '*/30',
                '3' => str_pad(rand(1, 48), 2, '0', STR_PAD_LEFT),
                '4' => str_pad(rand(10, 55), 2, '0', STR_PAD_LEFT),
                '5' => str_pad(rand(1, 35), 2, '0', STR_PAD_LEFT),
                '6' => str_pad(rand(5, 55), 2, '0', STR_PAD_LEFT),
                '7' => str_pad(rand(3, 55), 2, '0', STR_PAD_LEFT),
                '8' => str_pad(rand(1, 50), 2, '0', STR_PAD_LEFT),
                '9' => str_pad(rand(1, 45), 2, '0', STR_PAD_LEFT),
            );
            $hours = array(
                '1' => '*',
                '2' => '*',
                '3' => '*/01',
                '4' => '*/03',
                '5' => '*/06',
                '6' => '*/12',
                '7' => '*',
                '8' => '*',
                '9' => '*',
            );
            $days = array(
                '1' => '*',
                '2' => '*',
                '3' => '*',
                '4' => '*',
                '5' => '*',
                '6' => '*',
                '7' => '*/01',
                '8' => '*/02',
                '9' => '*/03',
            );
            if (!isset($days[$this->cron_general]))
                $this->cron_general = 2;

            $this->load_period_days = $days[$this->cron_general];
            $this->load_period_hours = $hours[$this->cron_general];
            $this->load_period_minutes = $minutes[$this->cron_general];

            return true;
        }
        return false;
    }

    public function afterSave() {
        parent::afterSave();

        if ($this->scenario != 'runCronTab') {
            $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/'); // my_crontab file will store all added jobs
            $jobs_obj = $cron->getJobs();

            foreach ($jobs_obj as $key => $value) {
                $p = $value->getParams();
                if (isset($p[0])) {
                    $temp_delete_model = PricesFtpAutoloadRules::model()->findByPk($p[0]);
                    if (($value->getCommandName() == 'FtpPriceLoad' && $temp_delete_model == NULL) || ($value->getCommandName() == 'FtpPriceLoad' && $p[0] == $this->id))
                        $cron->removeJob($key);
                }
            }

            if ($this->active_state == '1') {
                $job = new CronApplicationJob('protected/yiic', 'FtpPriceLoad', array("'datetime"), $this->load_period_minutes, $this->load_period_hours, $this->load_period_days); // run every day
                $job->setParams(array($this->id));
                $cron->add($job);
            } else {
                $db = Yii::app()->db;
                $rows = $db->createCommand('SELECT `id` FROM ' . Prices::model()->tableName() . '  WHERE  `store_id`=' . $this->store_id)->queryAll();
                $ids = array(0);
                foreach ($rows as $value) {
                    $ids[] = '`price_id`=' . $value['id'];
                }
                $ids2 = array(0);
                foreach ($rows as $value) {
                    $ids2[] = '`id`=' . $value['id'];
                }

                $dataModel = new PricesData;
                $db->createCommand('DELETE FROM ' . $dataModel->tableName() . '  WHERE  ' . implode(' OR ', $ids))->query();
                $db->createCommand('DELETE FROM ' . Prices::model()->tableName() . '  WHERE  ' . implode(' OR ', $ids2))->query();
            }

            $cron->saveCronFile(); // save to my_crontab cronfile
            $cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
        }
    }

    public function init() {
        parent::init();
        if (empty($this->ftp_destination_folder)) {
            $this->ftp_destination_folder = '/';
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            //array('brand, name, price, quantum, article, delivery, charset', 'required'),
            array('charset', 'required'),
            array('ftp_auth_type, active_state, store_id, start_line, finish_line, delete_state, download_count, cron_general, mail_id, send_admin_mail', 'numerical', 'integerOnly' => true),
            array('replace_delivery, replace_brand, replace_name, replace_price, replace_quantum, replace_article, replace_multiply, ftp_server, ftp_login, ftp_password, delivery, brand, name, price, quantum, article, multiply, xml_element_tag, rule_name', 'length', 'max' => 127),
            array('ftp_destination_folder, search_file_criteria', 'length', 'max' => 255),
            array('load_period_days, load_period_hours, load_period_minutes', 'length', 'max' => 32),
            array('download_time ', 'length', 'max' => 20),
            array('method_type', 'length', 'max' => 10),
            array('mail_subject, mail_body, mail_from, mail_file', 'length', 'max' => 127),
            array('remote_url', 'url'),
            //array('cron_general ', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, ftp_server, ftp_login, ftp_password, ftp_auth_type, ftp_destination_folder, search_file_criteria, delivery, active_state, store_id, load_period, start_line, finish_line, brand, name, price, quantum, article, multiply, delete_state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ftp_server' => Yii::t('prices', 'Server address'),
            'rule_name' => Yii::t('prices', 'name of rule'),
            'download_time' => Yii::t('prices', 'Time of the last processing'),
            'download_count' => Yii::t('prices', 'Number of loaded positions'),
//            '' => '',
//            '' => '',
            'ftp_login' => Yii::t('prices', 'login'),
            'ftp_password' => Yii::t('prices', 'Password'),
            'ftp_auth_type' => Yii::t('prices', 'Entrance type on the server'),
            'ftp_destination_folder' => Yii::t('prices', 'Folder on the server'),
            'search_file_criteria' => Yii::t('prices', 'Criterion for filter files'),
            'active_state' => Yii::t('prices', 'Included'),
            'store_id' => Yii::t('prices', 'Storage'),
            'load_period_days' => Yii::t('prices', 'Download time  (days)'),
            'load_period_minutes' => Yii::t('prices', 'Download time  (minute)'),
            'load_period_hours' => Yii::t('prices', 'Download time (hour)'),
            'cron_general' => Yii::t('prices', 'Download time '),
//            'load_period_' => 'Время загрузки',
            'start_line' => Yii::t('prices', 'Start line'),
            'finish_line' => Yii::t('prices', 'Last line'),
            'delivery' => Yii::t('prices', 'Delivery  (column)'),
            'brand' => Yii::t('prices', 'Manufacturer  (column)'),
            'name' => Yii::t('prices', 'Name  (column)'),
            'price' => Yii::t('prices', 'Price  (column)'),
            'quantum' => Yii::t('prices', 'Number  (column)'),
            'article' => Yii::t('prices', 'Article  (column)'),
            'multiply' => Yii::t('prices', 'Multiply  (column)'),
            'replace_delivery' => Yii::t('prices', 'Delivery (autocomplete)'),
            'replace_brand' => Yii::t('prices', 'Manufacturer  (autocomplete)'),
            'replace_name' => Yii::t('prices', 'Name  (autocomplete)'),
            'replace_price' => Yii::t('prices', 'Price  (autocomplete)'),
            'replace_quantum' => Yii::t('prices', 'Number  (autocomplete)'),
            'replace_article' => Yii::t('prices', 'Article (autocomplete)'),
            'replace_multiply' => Yii::t('prices', 'Multiply (autocomplete)'),
            'delete_state' => Yii::t('prices', 'Autodelete'),
            'charset' => Yii::t('prices', 'Coding'),
            'xml_element_tag' => Yii::t('prices', 'Xml element tag'),
            'mail_file' => Yii::t('prices', 'The name of the file contains'),
            'mail_id' => Yii::t('prices', 'Mailbox'),
            'mail_from' => Yii::t('prices', 'Came from what address'),
            'mail_body' => Yii::t('prices', 'In the text of the letter is contained'),
            'mail_subject' => Yii::t('prices', 'In the field the subject of the letter contains'),
            'send_admin_mail' => Yii::t('prices', 'The report on the administrator\'s mail'),
            'method_type' => Yii::t('prices', 'Loading method'),
//            '' => '',
        );
    }

    public function convert($dir, $filename) {
        $this->_start_line = $this->start_line;
        $this->_finish_line = $this->finish_line;
        $text = '';
        $check_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($check_ext == 'txt' || $check_ext == 'csv') {
            $text = $this->convertTxt($dir, $filename);
        }
        if ($check_ext == 'xls') {
            $text = $this->convertXls($dir, $filename);
        }
        if ($check_ext == 'xml') {
            return $this->convertXml($dir, $filename);
        }
        if ($check_ext == 'xlsx') {
            $text = $this->convertXlsx($dir, $filename);
        }
        $this->start_line = $this->_start_line;
        $this->finish_line = $this->_finish_line;
        if (empty($text))
            return false;
        else
            return $text;
    }

    public function convertXlsx($dir, $filename) {
//        echo memory_get_usage() . "<br>\n";
        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок доставки' . "\n";
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once ($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        include_once ($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel/IOFactory.php');
        spl_autoload_register(array('YiiBase', 'autoload'));
        $startRow = $this->start_line;
        $inputFileType = 'Excel2007';
        $chunkSize = 10000;
        $chunkFilter = new PHPExcelBigFileFilter;
        $highestColumn = 10;
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        $exit = true;
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $chunkFilter = new PHPExcelBigFileFilter;
        $objReader->setReadFilter($chunkFilter);
        while ($exit) {
            $exit = false;
            $chunkFilter->setRows($startRow, $chunkSize);
            $objPHPExcel = $objReader->load($dir . $filename);
            $sheet = $objPHPExcel->getSheet(0);
            $highestColumn = $sheet->getHighestColumn();
            $highestRow = $sheet->getHighestRow();
            $rowData = $sheet->rangeToArray('A' . $startRow . ':' . $highestColumn . ($startRow + $chunkSize - 1 < $startRow + $highestRow ? ($startRow + $chunkSize - 1) : $startRow + $highestRow ), NULL, TRUE, FALSE);
            foreach ($rowData as $value) {
                foreach ($value as $key => $v) {
                    $value[$key] = str_replace($search, $replace, $v);
                }
                $data_vv = array();
                $fields = array(
                    'article',
                    'brand',
                    'name',
                    'price',
                    'quantum',
                    'delivery',
                );
                foreach ($fields as $vv) {
                    if (!isset($value[$this->{$vv} - 1])) {
                        $data_vv[$vv] = '';
                    } else {
                        $data_vv[$vv] = $value[$this->{$vv} - 1];
                    }
                }
                foreach ($fields as $vv) {
                    if (!empty($this->{'replace_' . $vv})) {
                        $data_vv[$vv] = $this->{'replace_' . $vv};
                    }
                }
                $export .= $data_vv['brand'] . ';' . $data_vv['article'] . ';' . $data_vv['name'] . ';' . $data_vv['price'] . ';' . $data_vv['quantum'] . ';' . $data_vv['delivery'] . "\n";
                if ($startRow + $chunkSize - 1 < $startRow + $highestRow)
                    $exit = true;
            }
            unset($rowData);
            $sheet->disconnectCells();
            $startRow += $chunkSize;
            unset($objPHPExcel);
        }
        return $export;
    }

    public function convertXml($dir, $filename) {
        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок доставки' . "\n";


        $file = file_get_contents($dir . $filename);
        $dom = new DOMDocument;
        $dom->loadXML($file);

        $elements = $dom->getElementsByTagName($this->xml_element_tag);

        $elemements_types = array(
            'price' => $this->price,
            'quantum' => $this->quantum,
            'article' => $this->article,
            'brand' => $this->brand,
            'name' => $this->name,
            'delivery' => $this->delivery,
        );
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        $delivery = $this->delivery;
        if (empty($this->start_line)) {
            $this->start_line = 1;
        }
        $i = 1;
        foreach ($elements as $element) {
            if (!empty($this->finish_line) && $i > $this->finish_line)
                break;
            if ($i < $this->start_line)
                continue;
            $elem = XmlWork::getArray($element);
            $data = array();
            foreach ($elemements_types as $key => $value) {
                $data[$key] = str_replace($search, $replace, (isset($elem[$value][0]['#text']) ? $elem[$value][0]['#text'] : $elem[$value][0]['#cdata-section']));
            }
//            $export .= $data['brand'] . ';' . $data['article'] . ';' . $data['name'] . ';' . $data['price'] . ';' . $data['quantum'] . ';' . $data['delivery'] . "\n";

            $data_vv = array();
            $fields = array(
                'article',
                'brand',
                'name',
                'price',
                'quantum',
                'delivery',
            );
            foreach ($fields as $vv) {
                $data_vv[$vv] = $data[$vv];
            }
            foreach ($fields as $vv) {
                if (!empty($this->{'replace_' . $vv})) {
                    $data_vv[$vv] = $this->{'replace_' . $vv};
                }
            }
            $export .= $data_vv['brand'] . ';' . $data_vv['article'] . ';' . $data_vv['name'] . ';' . $data_vv['price'] . ';' . $data_vv['quantum'] . ';' . $data_vv['delivery'] . "\n";

            $i++;
        }
        return $export;
    }

    public function convertXls($dir, $filename) {
        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок доставки' . "\n";
        $data = new JPhpExcelReader($dir . $filename);
        if (empty($this->start_line)) {
            $this->start_line = 1;
        }
        $sheet_num = 0;
        if (isset($data->sheets[0]) && isset($data->sheets[1]) && $data->sheets[0]['numRows'] == 0 && $data->sheets[1]['numRows'] != 0) {
            $sheet_num = 1;
        }
        if (empty($this->finish_line))
            $this->finish_line = $data->sheets[$sheet_num]['numRows'];
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        for ($i = $this->start_line; $i <= $this->finish_line; $i++) {

            $value = $data->sheets[$sheet_num]['cells'][$i];
            foreach ($value as $key => $v) {
                $value[$key] = str_replace($search, $replace, $v);
            }
            $data_vv = array();
            $fields = array(
                'article',
                'brand',
                'name',
                'price',
                'quantum',
                'delivery',
            );

            foreach ($fields as $vv) {
                if (!isset($value[$this->{$vv}])) {
                    $data_vv[$vv] = '';
                } else {
                    $data_vv[$vv] = $value[$this->{$vv}];
                }
            }
            foreach ($fields as $vv) {
                if (!empty($this->{'replace_' . $vv})) {
                    $data_vv[$vv] = $this->{'replace_' . $vv};
                }
            }
            $export .= $data_vv['brand'] . ';' . $data_vv['article'] . ';' . $data_vv['name'] . ';' . $data_vv['price'] . ';' . $data_vv['quantum'] . ';' . $data_vv['delivery'] . "\n";
        }
        return $export;
    }

    public function convertTxt($dir, $filename) {
        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок доставки;Кратность' . "\n";
        $file = file($dir . $filename);
        if (empty($this->start_line)) {
            $this->start_line = 1;
        }
        if (empty($this->finish_line))
            $this->finish_line = count($file);
        $separator = ";";
        $string = explode($separator, trim($file[$this->start_line - 1]));
        if (count($string) < 5)
            $separator = "\t";
        $string = explode($separator, trim($file[$this->start_line - 1]));
        if (count($string) < 5) {
            CronLogs::log(Yii::t('prices', 'Incorrect file separator'), 'PricesFtpAutoloadRules');
            return '';
        }
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        for ($i = $this->start_line - 1; $i < $this->finish_line; $i++) {
            if (mb_detect_encoding($file[$i]) == $this->charset || $this->charset != 'UTF-8')
                $file[$i] = iconv($this->charset, 'UTF-8//IGNORE', $file[$i]);
            else {
                CronLogs::log(Yii::t('prices', 'Incorrect coding'), 'PricesFtpAutoloadRules');
                continue;
            }
            $value = explode($separator, trim($file[$i]));
            if (count($string) < 6)
                continue;
            foreach ($value as $key => $v) {
                $value[$key] = str_replace($search, $replace, $v);
            }
            $data_vv = array();
            $fields = array(
                'article',
                'brand',
                'name',
                'price',
                'quantum',
                'delivery',
                'multiply',
            );
            foreach ($fields as $vv) {
                if (!isset($value[$this->{$vv} - 1])) {
                    $data_vv[$vv] = '';
                } else {
                    $data_vv[$vv] = $value[$this->{$vv} - 1];
                }
            }
            foreach ($fields as $vv) {
                if (!empty($this->{'replace_' . $vv})) {
                    $data_vv[$vv] = $this->{'replace_' . $vv};
                }
            }
            $export .= $data_vv['brand'] . ';' . $data_vv['article'] . ';' . $data_vv['name'] . ';' . $data_vv['price'] . ';' . $data_vv['quantum'] . ';' . $data_vv['delivery'] . ';' . $data_vv['multiply'] . "\n";
            unset($file[$i]);
        }
        return $export;
    }

    public function savePrice($filename) {
        $model = new Prices;
        $model->saveFile = $filename;
        $model->store_id = $this->store_id;
        $model->rule_id = $this->id;
        $store_model = Stores::model()->findByPk($this->store_id);
        $model->price_group_1 = $store_model->price_group_1;
        $model->price_group_2 = $store_model->price_group_2;
        $model->price_group_3 = $store_model->price_group_3;
        $model->price_group_4 = $store_model->price_group_4;
        $model->delivery = $store_model->delivery;
        $model->supplier_inn = $store_model->supplier_inn;
        $model->supplier = $store_model->supplier;
        $model->currency = $store_model->currency;
        $model->search_state = $store_model->search_state;
        $model->isProgramWork = true;
        if (!$this->first_delete) {
            $model->isClear = $this->delete_state;
        }
        
        $model->name = pathinfo($this->filename, PATHINFO_BASENAME);

        if ($model->save()) {
            $prices = Prices::model()->findAllByAttributes(array('rule_id' => $this->id));
            $count = count($prices);
            for ($i = 0; $i < $count; $i ++) {
                if ($prices[$i]->primaryKey != $model->primaryKey) {
                    $prices[$i]->delete();
                }
            }
        }

        $this->first_delete = true;
        return $model->download_count;
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
        $criteria->compare('ftp_server', $this->ftp_server, true);
        $criteria->compare('ftp_login', $this->ftp_login, true);
        $criteria->compare('ftp_password', $this->ftp_password, true);
        $criteria->compare('ftp_auth_type', $this->ftp_auth_type);
        $criteria->compare('ftp_destination_folder', $this->ftp_destination_folder, true);
        $criteria->compare('search_file_criteria', $this->search_file_criteria, true);
        $criteria->compare('delivery', $this->delivery, true);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('store_id', $this->store_id);
        $criteria->compare('load_period_hours', $this->load_period_hours, true);
        $criteria->compare('load_period_days', $this->load_period_days, true);
        $criteria->compare('load_period_minutes', $this->load_period_minutes, true);
        $criteria->compare('start_line', $this->start_line);
        $criteria->compare('finish_line', $this->finish_line);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('quantum', $this->quantum, true);
        $criteria->compare('article', $this->article, true);
        $criteria->compare('delete_state', $this->delete_state);

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
     * @return PricesFtpAutoloadRules the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
