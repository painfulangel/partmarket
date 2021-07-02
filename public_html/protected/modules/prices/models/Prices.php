<?php
/**
 * This is the model class for table "prices".
 *
 * The followings are the available columns in table 'prices':
 * @property string $id
 * @property string $name
 * @property string $price_group_1
 * @property string $price_group_2
 * @property string $price_group_3
 * @property integer $price_group_4
 * @property integer $active_state
 * @property string $delivery
 * @property string $multiply
 * @property string $supplier_inn
 * @property string $supplier
 * @property string $user_id
 * @property string $create_date
 * @property string $currency
 * @property string $store_id
 * @property integer $search_state
 * @property string $rule_id
 * @property string $language
 * @property string $rule_name
 * @property string $store_name
 * @property integer $count_position
 */
class Prices extends CMyActiveRecord {
    /**
     *
     * @var String upload file
     */
    public $priceFile = null;
    public $saveFile = null;

    /**
     *
     * @var Boolean 
     */
    public $isClear = false;

    /**
     *
     * @var Boolean 
     */
    public $isProgramWork = true;

    /**
     *
     * @var String  charset of file
     */
    public $priceCharset = 'cp1251';
    public $download_count = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('price_group_1, price_group_2, price_group_3, price_group_4,  store_id', 'required'),
            array('priceCharset', 'required', 'on' => 'create'),
            array('priceFile', 'file', 'allowEmpty' => false, 'types' => 'txt, csv, xls', 'maxSize' => Yii::app()->getModule('prices')->maxFileSize, 'on' => 'create'),
            array('price_group_1, price_group_2, price_group_3, price_group_4, active_state, search_state,  user_id, currency, rule_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 127),
            array('multiply', 'safe'),
            array('delivery', 'length', 'max' => 255),
            array('supplier_inn, create_date', 'length', 'max' => 20),
            array('supplier', 'length', 'max' => 45),
            array('language', 'length', 'max' => 10),
            array('processed', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, language, name, price_group_1, price_group_2, price_group_3, price_group_4, active_state, delivery, supplier_inn, supplier, user_id, create_date, currency, processed', 'safe', 'on' => 'search'),
        );
    }

    public function importPriceFrom1c($filename) {
        $prices = Prices::model()->findAll(" `name` LIKE '%1c_export(%'");
        foreach ($prices as $model) {
            $model->deleteAllSubPrices();
        }

        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок поставки'."\n";

        $file = file_get_contents($filename);
        $dom = new DOMDocument;
        $dom->loadXML($file);

        $elements = $dom->getElementsByTagName('detal');

        $elemements_types = array(
            'price' => 'cost',
            'quantum' => 'availability',
            'article' => 'oem',
            'brand' => 'make_name',
            'name' => 'detail_name',
            'delivery' => 'delivery',
            'supplier' => 'supplier',
            'supplier_inn' => 'supplier_inn',
        );
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

        $i = 1;
        $supl_list = array();
        foreach ($elements as $element) {
            $elem = XmlWork::getArray($element);
            $data = array();
            foreach ($elemements_types as $key => $value) {
                $data[$key] = str_replace($search, $replace, (isset($elem[$value][0]['#text']) ? $elem[$value][0]['#text'] : $elem[$value][0]['#cdata-section']));
            }
//            $export .= $data['brand'].';'.$data['article'].';'.$data['name'].';'.$data['price'].';'.$data['quantum'].';'.$data['delivery']."\n";
            if (!isset($supl_list[$data['supplier_inn']]))
                $supl_list[$data['supplier_inn']] = array();
            $supl_list[$data['supplier_inn']][] = $data;
            $i++;
        }
        $dataModel = new PricesData;

//        print_r($supl_list);
        $db = Yii::app()->db;
        foreach ($supl_list as $key => $price_supl) {
            $values = '';
            $z = 0;
            $model = Prices::model()->findByAttributes(array('name' => "1c_export($key)"));
            $store_model = Stores::model()->findByAttributes(array('name' => '1c_export'));
            if ($model == NULL) {
                $model = new Prices;
                $model->name = "1c_export($key)";
                if ($store_model != NULL) {
                    $model->store_id = $store_model->id;
                    $model->price_group_1 = $store_model->price_group_1;
                    $model->price_group_2 = $store_model->price_group_2;
                    $model->price_group_3 = $store_model->price_group_3;
                    $model->price_group_4 = $store_model->price_group_4;
                    $model->currency = $store_model->currency;
                }
                $model->scenario = '1c';
                $model->supplier_inn = $price_supl['supplier_inn'];
                $model->supplier = $price_supl['supplier'];
                $model->save();
            }
//            $model->deleteAllSubPrices();
            foreach ($price_supl as $data) {
                $brand = $data['brand'];
                $original_article = $data['article'];
                $name = $data['name'];
                $price = $data['price'];
                $quantum = $data['quantum'];
                $delivery = $data['delivery'];
                $article = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $original_article));
                if (!empty($values))
                    $values.=",\n";
                $values .= "('$model->id','$brand','$original_article','$name','$price','$quantum','$delivery','$article')";
                $z++;
                if ($z > 1000) {
                    $z = 0;
                    $db->createCommand('INSERT INTO '.$dataModel->tableName().' (`price_id`, `brand`, `original_article`, `name`, `price`, `quantum`, `delivery`, `article`) VALUES '.$values)->query();
                    $values = '';
                }
            }
            if ($z != 0)
                $db->createCommand('INSERT INTO '.$dataModel->tableName().' (`price_id`, `brand`, `original_article`, `name`, `price`, `quantum`, `delivery`, `article`) VALUES '.$values)->query();
        }
    }

    public function beforeSave() {
        if ($this->isNewRecord && $this->saveFile == NUll) {
            $temp = CUploadedFile::getInstance($this, 'priceFile');
            if (empty($this->name))
                $this->name = $temp;
        }
        if (parent::beforeSave()) {
            $m = Stores::model()->findByPk($this->store_id);
            if ($m == NULL) {
                $this->store_id = Yii::app()->db->createCommand('SELECT `id` FROM `'.Stores::model()->tableName().'` LIMIT 1')->queryScalar();
            }
            $m = PricesRulesGroups::model()->findByPk($this->price_group_1);
            if ($m == NULL) {
                $this->price_group_1 = Yii::app()->db->createCommand('SELECT `id` FROM `'.PricesRulesGroups::model()->tableName().'` LIMIT 1')->queryScalar();
            }
            $m = PricesRulesGroups::model()->findByPk($this->price_group_2);
            if ($m == NULL) {
                $this->price_group_2 = Yii::app()->db->createCommand('SELECT `id` FROM `'.PricesRulesGroups::model()->tableName().'` LIMIT 1')->queryScalar();
            }
            $m = PricesRulesGroups::model()->findByPk($this->price_group_3);
            if ($m == NULL) {
                $this->price_group_3 = Yii::app()->db->createCommand('SELECT `id` FROM `'.PricesRulesGroups::model()->tableName().'` LIMIT 1')->queryScalar();
            }
            $m = PricesRulesGroups::model()->findByPk($this->price_group_4);
            if ($m == NULL) {
                $this->price_group_4 = Yii::app()->db->createCommand('SELECT `id` FROM `'.PricesRulesGroups::model()->tableName().'` LIMIT 1')->queryScalar();
            }

            if ($this->isNewRecord) {
                $store_model = Stores::model()->findByPk($this->store_id);
                if ($store_model->auto_delete_state == 1 || ($this->saveFile != null && $this->isClear) || ($this->isClear && $this->isProgramWork)) {
                    if ($this->isClear && $this->isProgramWork) {

                        $db = Yii::app()->db;
                        $rows = $db->createCommand('SELECT `id` FROM '.$this->tableName().'  WHERE  `store_id`='.$this->store_id)->queryAll();
                        $ids = array(0);
                        foreach ($rows as $value) {
                            $ids[] = '`price_id`='.$value['id'];
                        }
                        $ids2 = array(0);
                        foreach ($rows as $value) {
                            $ids2[] = '`id`='.$value['id'];
                        }

                        $dataModel = new PricesData;
//                        Yii::log('DELETE FROM '.$dataModel->tableName().'  WHERE  '.implode(' OR ', $ids));
                        $db->createCommand('DELETE FROM '.$dataModel->tableName().'  WHERE  '.implode(' OR ', $ids))->query();
                        $db->createCommand('DELETE FROM '.$this->tableName().'  WHERE  '.implode(' OR ', $ids2))->query();
                    }
                    if (!$this->isProgramWork) {
                        $db = Yii::app()->db;

                        $data = $db->createCommand('(SELECT * FROM '.$this->tableName().' WHERE store_id='.$this->store_id.' )')->queryAll();

                        $ids = array(0);
                        foreach ($data as $row) {
                            $ids[] = $row['id'];
                        }

                        $db->createCommand('DELETE FROM '.PricesData::model()->
                                        tableName().' WHERE '.implode(' AND id=', $ids))->query();
                        $db->createCommand('DELETE FROM '.$this->tableName().' WHERE store_id='.$this->store_id)->query();
                    }
                }

                $this->create_date = strtotime(date('d.m.Y H:i:s'));
            }
            return true;
        } else
            return false;
    }

    public function afterSave() {
        parent::afterSave();
        
        set_time_limit(0);

        if($this->scenario != 'queue'){
            if (($this->saveFile == null && $this->scenario != '1c') && ($this->priceFile != NULL || $this->isNewRecord)) {
                $this->priceFile = CUploadedFile::getInstance($this, 'priceFile');
                $filename = pathinfo($this->priceFile->getName());
                $extension = $filename['extension'];

                $filename = md5(time()).'.'.$extension;
                $this->priceFile->saveAs(Yii::app()->getModule('prices')->pathFiles.$filename);

                if ($extension == 'xls') {
                    $this->importXLS(Yii::app()->getModule('prices')->pathFiles.$filename);
                } else {
                    $this->importTXT(Yii::app()->getModule('prices')->pathFiles.$filename);
                }

                unlink(Yii::app()->getModule('prices')->pathFiles.$filename);
            } else if ($this->saveFile != null) {
                $this->importTXT($this->saveFile);
            }
        }


    }

    public function importTXT($filename)
    {
        if(file_exists($filename)){
            CronLogs::log('Import file ' . $filename);
            $file = file($filename);

            $n = count($file);
            $values = '';
            $dataModel = new PricesData;
            $db = Yii::app()->db;
            $z = 0;

            if ($n > 1) {
                $separator = ";";
                $string = explode($separator, trim($file[0]));

                if (count($string) < 5)
                    $separator = "\t";

//                $fields = '`price_id`, `brand`, `original_article`, `name`, `price`, `quantum`, `delivery`, `article`, `internal`, `price_selling`, `price2`, `price3`, `price4`, `price_selling2`, `price_selling3`, `price_selling4`, `storage`, `supplier`, `category`, `weight`, `dimensions`, `image`, `image2d`, `extra_field1`, `extra_field2`, `extra_field3`, `extra_field4`, `extra_field5`, `multiply`';
                $fields = '`price_id`, `brand`, `original_article`, `name`, `price`, `quantum`, `delivery`, `article`, `multiply`, `price_selling`, `price2`, `price3`, `price4`, `price_selling2`, `price_selling3`, `price_selling4`, `storage`, `supplier`, `category`, `weight`, `dimensions`, `image`, `image2d`, `extra_field1`, `extra_field2`, `extra_field3`, `extra_field4`, `extra_field5`';

                for ($i = 0; $i < $n; $i++) {
                    if (strlen($file[$i]) < 5)
                        continue;

                    /*ob_start();
                    echo '<pre>'; print_r($file[$i]); echo '</pre>';
                    file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/files.txt', ob_get_clean()."\n", FILE_APPEND);*/

                    /*try {
                        //$file[$i] = iconv($this->priceCharset, 'UTF-8', $file[$i]);
                        if ($this->saveFile == NULL) {
                            if ($this->saveFile != NULL || mb_detect_encoding($file[$i]) == $this->priceCharset || $this->priceCharset != 'UTF-8')
                                $file[$i] = iconv($this->priceCharset, 'UTF-8//IGNORE', $file[$i]);
                            else {
                                //if(!)
                                Yii::log(mb_detect_encoding($file[$i]));
                                continue;
                                //$this->isNewRecord = false;
                                //$this->delete();
                                //throw new CHttpException(400, 'Неправильная кодировка');
                            }
                        }
                    } catch (Exception $ex) {
                        CronLogs::log(Yii::t('prices', "Problems with coding. Or it is incorrectly specified, or contain characters not supported by the encoding.").' '.Yii::t('prices', "Rule #").$this->rule_id, Yii::t('prices', 'Startup'));
                    }*/

                    $string = explode($separator, trim($file[$i]));

                    if (count($string) < 5)
                        continue;
                    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
                    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

                    //1. Producer
                    $brand = str_replace($search, $replace, $string[0]);

                    //2. Articul
                    $original_article = str_replace($search, $replace, $string[1]);
                    $article = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $original_article));

                    //3. Name
                    $name = str_replace($search, $replace, $string[2]);

                    //4. Purchase price
                    $price = $this->processPrice($string[3]);

                    //5. Quantity
                    $quantum = intval(str_replace(",", ".", $string[4]));
                    
                    $quantum = str_replace($search, $replace, $quantum);
                    $quantum = str_replace('*', '', $quantum);
                    $quantum = preg_replace('#([0-9]+)([,])([0-9]+)([\.])([0-9]+)#', '$1$3', $quantum);
                    $quantum = str_replace(array(',', ' ', "\t"), array('.', '', ''), $quantum);
                    //$quantum = str_replace(array('.', '00'), '', $quantum);
                    $quantum = str_replace('.', '', $quantum);
                    $quantum = intval($quantum);

                    //6. Delivery
                    $delivery = str_replace($search, $replace, $string[5]);

//                    //7. Internal number
//                    $internal = array_key_exists(6, $string) ? trim($string[6]) : '';
                    //7. multiply
                    $multiply = array_key_exists(6, $string) ? trim($string[6]) : '';


                    //8. Selling price
                    $selling = array_key_exists(7, $string) ? $this->processPrice($string[7]) : '';

                    //9. Purchase price 2
                    $price2 = array_key_exists(8, $string) ? $this->processPrice($string[8]) : '';

                    //10. Purchase price 3
                    $price3 = array_key_exists(9, $string) ? $this->processPrice($string[9]) : '';

                    //11. Purchase price 4
                    $price4 = array_key_exists(10, $string) ? $this->processPrice($string[10]) : '';

                    //12. Selling price 2
                    $selling2 = array_key_exists(11, $string) ? $this->processPrice($string[11]) : '';

                    //13. Selling price 3
                    $selling3 = array_key_exists(12, $string) ? $this->processPrice($string[12]) : '';

                    //14. Selling price 4
                    $selling4 = array_key_exists(13, $string) ? $this->processPrice($string[13]) : '';

                    //15. Storage
                    $storage = array_key_exists(14, $string) ? trim($string[14]) : '';

                    //16. Supplier
                    $supplier = array_key_exists(15, $string) ? trim($string[15]) : '';

                    //17. Category
                    $category = array_key_exists(16, $string) ? trim($string[16]) : '';

                    //18. Weight
                    $weight = array_key_exists(17, $string) ? trim($string[17]) : '';

                    //19. Dimensions
                    $dimensions = array_key_exists(18, $string) ? trim($string[18]) : '';

                    //20.Image
                    $image = array_key_exists(19, $string) ? trim($string[19]) : '';

                    //21. Image 2D
                    $image2d = array_key_exists(20, $string) ? trim($string[20]) : '';

                    //22. Additional 1
                    $extra1 = array_key_exists(21, $string) ? trim($string[21]) : '';

                    //23. Additional 2
                    $extra2 = array_key_exists(22, $string) ? trim($string[22]) : '';

                    //24. Additional 3
                    $extra3 = array_key_exists(23, $string) ? trim($string[23]) : '';

                    //25. Additional 4
                    $extra4 = array_key_exists(24, $string) ? trim($string[24]) : '';

                    //26. Additional 5
                    $extra5 = array_key_exists(25, $string) ? trim($string[25]) : '';

                    //27. Additional 5
//                    $multiply = array_key_exists(26, $string) ? trim($string[26]) : '';

                    if (!empty($values))
                        $values .= ",\n";

                    $values .= "('$this->id',' $brand','$original_article ','$name ','$price','$quantum','$delivery','$article', '$multiply', '$selling', '$price2', '$price3', '$price4', '$selling2', '$selling3', '$selling4', '$storage', '$supplier', '$category', '$weight', '$dimensions', '$image', '$image2d', '$extra1', '$extra2', '$extra3', '$extra4', '$extra5')";


                    $this->download_count++;

                    $z++;

                    //Если строк для вставки больше 1000 сбрасываем счетчик и делаем INSERT
                    if ($z > 1000) {
                        $z = 0;
                        $db->createCommand('INSERT INTO '.$dataModel->tableName().'('.$fields.') VALUES '.$values)->query();
                        $values = '';
                    }

                    unset($file[$i]);
                }

                //Если строк меньше 1000 делаем INSERT
                if ($z != 0){
                    $db->createCommand('INSERT INTO '.$dataModel->tableName().'('.$fields.') VALUES '.$values)->query();
                }

            }
        }
    }
    
    private function processPrice($price) {
    	$price = str_replace('*', '', trim($price));
    	$price = preg_replace('#([0-9]+)([,])([0-9]+)([\.])([0-9]+)#', '$1$3$4$5', $price);
    	$price = str_replace(array(',', ' ', "\t"), array('.', '', ''), $price);
    	return (float) $price;
    }

    /**
     * 
     * @param type $filename
     */
    public function importXLS($filename) {
        if(file_exists($filename)){
            $data = new JPhpExcelReader($filename);
            $values = '';
            $db = Yii::app()->db;
            $dataModel = new PricesData;
            $z = 0;

            $fields = '`price_id`, `brand`, `original_article`, `name`, `price`, `quantum`, `delivery`, `article`, `internal`, `price_selling`, `price2`, `price3`, `price4`, `price_selling2`, `price_selling3`, `price_selling4`, `storage`, `supplier`, `category`, `weight`, `dimensions`, `image`, `image2d`, `extra_field1`, `extra_field2`, `extra_field3`, `extra_field4`, `extra_field5`';

            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
                $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

                //1. Producer
                $brand = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'] [$i][1]));

                //2. Articul
                $original_article = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][2]));
                $article = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $original_article));

                //3. Name
                $name = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][3]));

                //4. Purchase price
                $price = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][4]));

                //5. Quantity
                $quantum = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][5]));

                //6. Delivery
                $delivery = str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][6]));

                //7. Internal number
                $internal = array_key_exists(7, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][7])) : '';

                //8. Selling price
                $selling = array_key_exists(8, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][8])) : '';

                //9. Purchase price 2
                $price2 = array_key_exists(9, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][9])) : '';

                //10. Purchase price 3
                $price3 = array_key_exists(10, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][10])) : '';

                //11. Purchase price 4
                $price4 = array_key_exists(11, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][11])) : '';

                //12. Selling price 2
                $selling2 = array_key_exists(12, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][12])) : '';

                //13. Selling price 3
                $selling3 = array_key_exists(13, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][13])) : '';

                //14. Selling price 4
                $selling4 = array_key_exists(14, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][14])) : '';

                //15. Storage
                $storage = array_key_exists(15, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][15])) : '';

                //16. Supplier
                $supplier = array_key_exists(16, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][16])) : '';

                //17. Category
                $category = array_key_exists(17, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][17])) : '';

                //18. Weight
                $weight = array_key_exists(18, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][18])) : '';

                //19. Dimensions
                $dimensions = array_key_exists(19, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][19])) : '';

                //20.Image
                $image = array_key_exists(20, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][20])) : '';

                //21. Image 2D
                $image2d = array_key_exists(21, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][21])) : '';

                //22. Additional 1
                $extra1 = array_key_exists(22, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][22])) : '';

                //23. Additional 2
                $extra2 = array_key_exists(23, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][23])) : '';

                //24. Additional 3
                $extra3 = array_key_exists(24, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][24])) : '';

                //25. Additional 4
                $extra4 = array_key_exists(25, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][25])) : '';

                //26. Additional 5
                $extra5 = array_key_exists(26, $data->sheets[0]['cells'][$i]) ? str_replace($search, $replace, iconv($this->priceCharset, 'UTF-8', $data->sheets[0]['cells'][$i][26])) : '';

                if (!empty($values))
                    $values .=",\n";

                $values .= "('$this->id','$brand','$original_article','$name','$price','$quantum','$delivery','$article', '$internal', '$selling', '$price2', '$price3', '$price4', '$selling2', '$selling3', '$selling4', '$storage', '$supplier', '$category', '$weight', '$dimensions', '$image', '$image2d', '$extra1', '$extra2', '$extra3', '$extra4', '$extra5')";
                $z++;

                if ($z > 1000) {
                    $z = 0;
                    $db->createCommand('INSERT INTO '.$dataModel->tableName().'('.$fields.') VALUES '.$values)->query();
                    $values = '';
                }
            }
            if ($z != 0)
                $db->createCommand('INSERT INTO '.$dataModel->tableName().'('.$fields.') VALUES '.$values)->query();
        }
    }

    /**
     * 
     */
    public function deleteAllSubPrices() {
        $db = Yii::app()->db;
        $dataModel = new PricesData;
        Yii::log('|'.$this->id);
        $db->createCommand('DELETE FROM '.$dataModel->tableName().'  WHERE  `price_id`='.$this->id)->query();
    }

    /**
     * 
     * @return type
     */
    public function exportPrices() {
        $db = Yii::app()->db;
        $dataModel = new PricesData;


        $total_count = $db->createCommand('SELECT COUNT(*) FROM '.$dataModel->tableName().'  WHERE  `price_id`='.$this->id)->queryScalar();

        $start_limit = 0;
        $limit_total = 10000;
        while ($start_limit < $total_count) {

            $data = $db->createCommand('SELECT * FROM '.$dataModel->tableName().'  WHERE  `price_id`='.$this->id.' LIMIT '.$start_limit.', '.$limit_total)->queryAll();
            $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок доставки;Кратность'."\n";
            foreach ($data as $value) {
//                $export .= $value['brand'].';'.$value['original_article'].';'.$value['name'].';'.$value['price'].';'.$value['quantum']."\n";
                $export .= $value['brand'].';'.$value['original_article'].';'.$value['name'].';'.$value['price'].';'.$value['quantum'].';'.$value['delivery'].';'.$value['multiply']."\n";
            }

            unset($data);
            $start_limit+=$limit_total;
            echo iconv('UTF-8', 'cp1251', $export);
//            die;
            unset($export);
            $export = '';
        }die;
        return $export;
    }

    /**
     * 
     * @return type
     */
    public function exportUserPrice() {
        $db = Yii::app()->db;
        $dataModel = new PricesData;
//        $data = $db->createCommand('SELECT * FROM '.$dataModel->tableName().'  WHERE  `price_id`='.$this->id)->queryAll();

        $m_prices = new Prices;
        $m_prices_data = new PricesData;
        $m_stores = new Stores;


        $params = array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup());

        $db = Yii::app()->db;
        $sql = 'SELECT COUNT(*)'
               .'FROM `'.$m_prices_data->tableName().'` `t` JOIN `'.$m_prices->tableName().'` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `'.$m_stores->tableName().'` `t_store`  ON t_price.store_id=`t_store`.`id` '
               .'WHERE `t_price`.`active_state`=\'1\' AND `t_price`.`search_state`=\'1\'  ';
        $total_count = $db->createCommand($sql)->queryScalar();
//        echo $total_count;

        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок поставки'."\n";
        $start_limit = 0;
        $limit_total = 20000;
        while ($start_limit < $total_count) {
            $sql = 'SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`,'
                   ."`t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_$params[price_group_id]` as `price_price_group`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, "
                   .' `t_store`.`name` as `store_name`,  `t_store`.`count_state` as `store_count_state` '
                   .'FROM `'.$m_prices_data->tableName().'` `t` JOIN `'.$m_prices->tableName().'` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `'.$m_stores->tableName().'` `t_store`  ON t_price.store_id=`t_store`.`id` '
                   .'WHERE `t_price`.`active_state`=\'1\' AND `t_price`.`search_state`=\'1\' LIMIT '.$start_limit.', '.$limit_total;
            $data = $db->createCommand($sql)->queryAll();


            foreach ($data as $value) {
                $delivery = is_numeric($value['delivery']) ? $value['delivery'] : 0 + is_numeric($value['price_delivery']) ? $value['price_delivery'] :
                                0;
                if ($delivery == 0)
                    $delivery = Yii::app()->getModule('detailSearch')->
                            zerosDeliveryValue;
                $price = Yii::app()->getModule('prices')->getPriceFunction($value);
                $price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price);


                $export .= $value['brand'].';'.$value['original_article'].';'.$value[
                        'name'].';'.$price_echo.';'.$value['quantum'].';'.$delivery."\n";



//            $this->data[$value['article']] = array(
//                'articul_order' => strtoupper($value['original_article']),
//                'supplier_inn' => $value['price_supplier_inn'],
//                'supplier' => $value['price_supplier'],
//                'store' => $value['store_name'],
//                'name' => $value['name'],
//                'brand' => $value['brand'],
//                'articul' => strtoupper($value['article']),
//                'dostavka' => $delivery,
//                'kolichestvo' => $value['quantum'],
//                'price_echo' => $price_echo,
//                'price' => $price,
//                'price_data_id' => $value['id'],
//                'store_count_state' => $value['store_count_state'],
//                'weight' => $value['weight'],
//                    //
//            );
            }
            unset($data);
            $start_limit+=$limit_total;
            echo iconv('UTF-8', 'cp1251', $export);
//            die;
            unset($export);
            $export = '';
        }
        die;
        return $export;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'store' => array(self::HAS_ONE, 'Stores', array('id' => 'store_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('prices', 'Name'),
            'store_id' => Yii::t('prices', 'Storage'),
            'price_group_1' => Yii::t('prices', 'Price group').' 1',
            'price_group_2' => Yii::t('prices', 'Price group').' 2',
            'price_group_3' => Yii::t('prices', 'Price group').' 3',
            'price_group_4' => Yii::t('prices', 'Price group').' 4',
            'create_date' => Yii::t('prices', 'Creation date'),
            'priceFile' => Yii::t('prices', 'File'),
            'priceCharset' => Yii::t('prices', 'Coding'),
            'delivery' => Yii::t('prices', 'Delivery date'),
            'supplier_inn' => Yii::t('prices', 'Supplier INN'),
            'supplier' => Yii::t('prices', 'Supplier'),
            'user_id' => Yii::t('prices', 'User'),
            'currency' => Yii::t('prices', 'Currency'),
            'auto_delete_state' => Yii::t('prices', 'When loading new prices - to delete the old'),
            'search_state' => Yii::t('prices', 'Show price list on the website'),
            'active_state' => Yii::t('prices', 'Active'),
            'language' => Yii::t('languages', 'Language'),
            'rule_name' => Yii::t('prices', 'Rule Name'),
            'store_name' => Yii::t('prices', 'Store Name'),
            'multiply' => Yii::t('prices', 'Multiply'),
            'count_position' => Yii::t('prices', 'Count Loaded Position'),
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

        $criteria->compare
                ('name', $this->name, true);
        $criteria->compare('price_group_1', $this->price_group_1);
        $criteria->compare('price_group_2', $this->price_group_2);
        $criteria->compare('price_group_3', $this->price_group_3);

        $criteria->compare('price_group_4', $this->price_group_4);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('search_state', $this->search_state);
        $criteria->compare('delivery', $this->delivery);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('create_date', @strtotime($this->create_date), true);
        $criteria->compare('currency', $this->currency);
        $criteria->compare('language', $this->language);

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
     * @return Prices the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getSuppliers() {
        $db = Yii::app()->db;
        $sql = 'SELECT supplier as `supplier`, supplier_inn   as `supplier_inn`  FROM `'.$this->tableName().'`  ';
        $data = $db->createCommand($sql)->queryAll();
        return $data;
    }

    /**
     * Получить количество файлов в очереди на обработку
     * @return string
     */
    public function getQueue()
    {
        $count = PricesAutoloadQueue::model()->countByAttributes(array(
            'rule_id'=>$this->rule_id
        ));

        return $count;
    }
}