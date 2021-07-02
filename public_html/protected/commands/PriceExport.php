<?php
class PriceExport extends CConsoleCommand {
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PricesExportRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function run($args) {
    	if (!defined('PLAN_TASK')) define('PLAN_TASK', 1);
    	/*ob_start();
    	echo '<pre>'; print_r($args); echo '</pre>';
    	file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', ob_get_clean()."\n", FILE_APPEND);*/
    	
        set_time_limit(100);
        $id = $args[0];
        
        $model = $this->loadModel($id);

        if ($model->active_state != '1')
            die;
        
        $search_price_id = array(0);
        foreach ($model->_stores AS $value) {
            if ($value['row_data'] == 1) {
                $search_price_id[] = $value['id'];
            }
        }

        $db = Yii::app()->db;
        $dataModel = new PricesData;
        $m_prices = new Prices;
        $m_prices_data = new PricesData;
        $m_stores = new Stores;

        $params = array('price_group_id' => $model->price_group);

        $db = Yii::app()->db;
        $sql = 'SELECT COUNT(*)'
               .'FROM `'.$m_prices_data->tableName().'` `t` JOIN `'.$m_prices->tableName().'` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `'.$m_stores->tableName().'` `t_store`  ON t_price.store_id=`t_store`.`id` '
               .'WHERE  t_price.store_id in ('.implode(', ', $search_price_id).') AND `t_price`.`active_state`=\'1\'   ';
        
        //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', $sql."\n\n", FILE_APPEND);
              
        $total_count = $db->createCommand($sql)->queryScalar();
        
        $export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок поставки'.($model->create_common && $model->sklad_otd ? ';Склад' : '')."\n";
        
        $start_limit = 0;
        $limit_total = 1000;
        $just_name = $id.time();
        
        $file_name = realpath(Yii::app()->basePath.'/../upload_files/export_prices').'/'.$just_name.'.csv';
        
    	//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', $file_name."\n", FILE_APPEND);
    	$values_layoff = array();
    	
    	$layoff = $model->create_common && $model->type_price_delivery && in_array(intval($model->type_price_delivery), array(1, 2, 3));
    	
        $file = fopen($file_name, 'w');
        
        $total = 0;
        while ($start_limit < $total_count) {
            $sql = 'SELECT `t`.`id` AS `id`, `t`.`name` AS `name`, `t`.`brand` AS `brand`, `t`.`price` AS `price`, `t`.`quantum` AS `quantum`, `t`.`article` AS `article`, `t`.`original_article` AS `original_article`, `t`.`delivery` AS `delivery`, `t`.`weight` AS `weight`,'
                   ."`t_price`.`id` AS `price_id`, `t_price`.`name` AS `price_name`, `t_price`.`delivery` AS `price_delivery`, `t_price`.`price_group_$params[price_group_id]` AS `price_price_group`, `t_price`.`supplier_inn` AS `price_supplier_inn`, `t_price`.`supplier` AS `price_supplier`, `t_price`.`currency` AS `price_currency`, "
                   .' `t_store`.`name` AS `store_name`,  `t_store`.`count_state` AS `store_count_state` '
                   .'FROM `'.$m_prices_data->tableName().'` `t` JOIN `'.$m_prices->tableName().'` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `'.$m_stores->tableName().'` `t_store`  ON t_price.store_id=`t_store`.`id` '
                   .'WHERE t_price.store_id in ('.implode(', ', $search_price_id).') AND `t_price`.`active_state`=\'1\'  LIMIT '.$start_limit.', '.$limit_total;
            
            //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', $sql."\n", FILE_APPEND);
            
            $data = $db->createCommand($sql)->queryAll();
            
            //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', count($data)."\n", FILE_APPEND);
            
            foreach ($data AS $value) {
                $value['brand'] = trim($value['brand']);
                $value['name'] = trim($value['name']);
                $value['original_article'] = trim($value['original_article']);
                if (empty($value['brand']) && empty($value['name']) && empty($value['original_article'])) continue;
            	
                //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', '2'."\n", FILE_APPEND);
            
                $total++;
                $delivery = is_numeric($value['delivery']) ? $value['delivery'] : 0 + is_numeric($value['price_delivery']) ? $value['price_delivery'] : 0;
                if ($delivery == 0) $delivery = $value['price_delivery'];
                if ($delivery == 0) $delivery = 'в наличии';
                
                $price = Yii::app()->getModule('prices')->getPriceFunction($value);
                $price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price);
                $price_echo = str_replace('.', ',', $price);

                if ($layoff) {
                	$original_article = preg_replace('/[^a-zа-я\d]+/ui', '', mb_strtolower(trim($value['original_article'])));
                	
                	$index = mb_strtolower($value['brand'].'_'.$original_article);
                	
                	$item = array('brand'      => $value['brand'], 
                				  'article'    => $value['original_article'], 
                				  'name'       => $value['name'], 
                				  'price'      => $price, 
                				  'price_echo' => $price_echo, 
                				  'quantum'    => $value['quantum'], 
                				  'delivery'   => $delivery, 
                				  'store_name' => $value['store_name']);
                	
                	if ($model->srok_more) {
                		if ($srok_days = intval($model->srok_days))
	                		if (is_numeric($item['delivery']) && $item['delivery'] > $srok_days) {
	                			continue;
	                		}
                	}
                	
                	if (!array_key_exists($index, $values_layoff)) {
                		$values_layoff[$index] = $item;
                	} else {
                		//ob_start(); echo '<pre>'; print_r($item); echo '</pre>';
                		//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', ob_get_clean()."\n", FILE_APPEND);
                		
	                	switch (intval($model->type_price_delivery)) {
	                		case 1:
	                			//with the lowest price
	                			if ($item['price'] < $values_layoff[$index]['price']) $values_layoff[$index] = $item;
	                		break;
	                		case 2:
	                			//with smaller delivery time
	                			if (!is_numeric($item['delivery']) || (is_numeric($item['delivery']) && is_numeric($values_layoff[$index]['delivery']) && $item['delivery'] < $values_layoff[$index]['delivery'])) $values_layoff[$index] = $item;
	                		break;
	                		case 3:
	                			//with the lowest price and smaller delivery time
	                			if ($item['price'] < $values_layoff[$index]['price'] || (!is_numeric($item['delivery']) || (is_numeric($item['delivery']) && is_numeric($values_layoff[$index]['delivery']) && $item['delivery'] < $values_layoff[$index]['delivery']))) $values_layoff[$index] = $item;
	                		break;
	                	}
                	}
                } else {
	                if ($model->human_machine == '1')
	                	$export .= $value['brand'].' ;'.'="'.$value['original_article'].'"; '.$value['name'].' ; '.$price_echo.'; '.$value['quantum'].' ; '.$delivery.($model->create_common && $model->sklad_otd ? '; '.$value['store_name'] : '')."\n";
	                else
	                	$export .= $value['brand'].' ;'.' '.$value['original_article'].' ; '.$value['name'].' ; '.$price_echo.'; '.$value['quantum'].' ; '.$delivery.($model->create_common && $model->sklad_otd ? '; '.$value['store_name'] : '')."\n";
	                
	                //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', '3'."\n", FILE_APPEND);
                }
            }
            
            //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', '12345'."\n", FILE_APPEND);
            
            unset($data);
            $start_limit+=$limit_total;
            
            if ($export != '') {
	            fwrite($file, iconv('UTF-8', 'cp1251//TRANSLIT', $export));
	            
	            unset($export);
	            $export = '';
            }
        }
        
        if ($layoff) {
        	ksort($values_layoff);
        	foreach ($values_layoff as $value) {
        		if ($model->human_machine == '1')
        			$string = $value['brand'].' ;'.'="'.$value['article'].'"; '.$value['name'].' ; '.$value['price_echo'].'; '.$value['quantum'].' ; '.$value['delivery'].($model->create_common && $model->sklad_otd ? '; '.$value['store_name'] : '')."\n";
        		else
        			$string = $value['brand'].' ;'.' '.$value['article'].' ; '.$value['name'].' ; '.$value['price_echo'].'; '.$value['quantum'].' ; '.$value['delivery'].($model->create_common && $model->sklad_otd ? '; '.$value['store_name'] : '')."\n";
        		
        		fwrite($file, iconv('UTF-8', 'cp1251//TRANSLIT', $string));
        	}
        }
        
        fclose($file);

        @unlink(realpath(Yii::app()->basePath.'/../upload_files/export_prices').'/'.$model->rule_name.'.csv');
        rename($file_name, realpath(Yii::app()->basePath.'/../upload_files/export_prices').'/'.$model->rule_name.'.csv');
        $file_name = realpath(Yii::app()->basePath.'/../upload_files/export_prices').'/'.$model->rule_name.'.csv';
        if ($model->email_send == '1' && !empty($model->email)) {
            $message = new YiiMailMessage();
            $message->setBody('Прайс "'.$model->rule_name.'" с сайта '.Yii::app()->config->get('Site.SiteName').' создан '.date('d.m.Y H:i:s').'.', 'text/html');
            $message->setSubject('Прайс "'.$model->rule_name.'" с сайта '.Yii::app()->config->get('Site.SiteName'));
            $emails = explode(',', $model->email);
            foreach ($emails as $email) {
                $message->addTo(trim($email));
            }
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            $message->attach(Swift_Attachment::fromPath($file_name));
            $recipient_count = Yii::app()->mail->send($message);
        }
        if ($model->ftp_send == '1' && !empty($model->ftp_server) && !empty($model->ftp_login)) {
            if (empty($model->ftp_password) && !empty($model->_ftp_password))
                $model->ftp_password = $model->_ftp_password;
            if (empty($model->ftp_password) || $model->ftp_password == null)
                $model->ftp_password = '';
            Yii::app()->params['ftp'] = array(
                'host' => $model->ftp_server,
                'port' => 21,
                'username' => $model->ftp_login,
                'password' => $model->ftp_password,
                'ssl' => false,
                'timeout' => 90,
                'autoConnect' => true,
            );
            $ftp = Yii::createComponent('ext.ftp.EFtpComponent');

            if (!$ftp->chdir(iconv('UTF-8', 'cp1251', $model->ftp_destination_folder))) {
                CronLogs::log('Ошибка входа в директорию автовыгрузки прайсов №'.$id, 'Автовыгрузка прайсов');
                @unlink($file_name);
                die;
            }
            $ftp->put($model->rule_name.'.csv', $file_name, FTP_BINARY);
        }
        @unlink($file_name);
        
        $model->scenario = 'saveDone';
        $model->download_count = $total;

        $model->download_time = time();
        $model->save();
    }
}