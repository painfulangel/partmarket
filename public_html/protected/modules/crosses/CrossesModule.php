<?php
class CrossesModule extends CWebModule {
    /**
     *
     * @var String function name for echo Radio buttons of extra characters
     */
    public $radionButtonFunction = 'radioButtonListInlineRow';

    /**
     *
     * @var Array extra encoding characters of input files
     */
    public $extraCharacters = array('cp1251' => 'cp1251', 'UTF-8' => 'utf');

    /**
     *
     * @var Integer Max size of uploaded files
     */
    public $maxFileSize = 26214400; //for max size 25mb

    /**
     *
     * @var String path fo upload files start with site path
     */
    public $pathExportFiles = '/upload_files/croses/';

    /**
     *
     * @var String path for upload files start with root file system
     */
    public $pathFiles = '';
    public $cross_server_name = 'http://cros.partexpert.net';
    public $cross_login = 'beta';
    public $cross_pass = 'partexpert';
    public $garanty = array();
	
    private $crosses = array();
    
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'crosses.models.*',
            'crosses.components.*',
        ));
        $this->pathFiles = realpath(Yii::app()->basePath.'/..'.$this->pathExportFiles).'/';
    }

    public function getGaranty($articul)
    {
        if (empty($this->garanty)) {
            $this->getCrosses($articul);
        }
        return $this->garanty;
    }
    
    public function getBrands($article_search)
    {
    	$brands = array();
    	
    	if ($article_search != '') {
    		$article_search = trim(mb_strtolower($article_search));
    		
    		//Our base
	    	$db = Yii::app()->db;
	    	
	    	$sql = 'SELECT DISTINCT `partsid` FROM `crosses_data` `t` '.
	    			"JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state='1' AND t.origion_article='$article_search'";
	    	
	    	$data = $db->createCommand($sql)->queryAll();
	    	$partsid = array(0);
	    	foreach ($data as $row) {
	    		$partsid[] = "`partsid`='$row[partsid]'";
	    	}
	    	
	    	$data_crosses = $db->createCommand('SELECT `origion_article` AS `article`, `origion_brand` AS `brand` FROM `crosses_data` `t` '.
	    									   'JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state = \'1\' AND ('.implode(' OR ', $partsid).')')->queryAll();
	    	
	    	$article_search = mb_strtoupper($article_search);
	    	
	    	foreach ($data_crosses as $row) {
				$brand = trim(mb_strtoupper($row['brand']));
				$article = trim(mb_strtoupper($row['article']));
				
				if ($article != $article_search) continue;
				
				$brands[$brand] = array('brand'      => $brand,
										'brand_link' => str_replace('/', '__', str_replace('%2F', '/', $brand)),
										'name'       => '',
										'article'    => $article);
	    	}
	    	//Our base
	    	
    		$url = $this->cross_server_name.'/api/?act=brands&auth_key='.$this->cross_pass.'&article='.$article_search.'&seo=on&nomination=on&brands=on';
    		
    		$data = @file_get_contents($url);
    		if (!empty($data)) {
    			$parts = new SimpleXMLElement($data);
    			
	    		foreach ($parts->article as $article) {
					$brand = trim(mb_strtoupper($article->brand));
					
					if ($brand != '')
						$brands[$brand] = array('brand'        => $brand,
												'brand_link'   => str_replace('/', '__', str_replace('%2F', '/', $brand)),
												'name'    	   => (string) $article->name,
												'article'      => mb_strtoupper((string) $article->article));
				}
    		}
    	}
    	
    	ksort($brands);
    	
    	return array_values($brands);
    }
    
    public function getCrossesWithBrandsAndAliases($articul, $brand = '')
    {
    	$temp_articul = $articul;
    	
    	$brand = trim(mb_strtolower($brand));
    	
    	$db = Yii::app()->db;
    	if (is_array($articul)) {
    		foreach ($articul as $k => $v) {
    			$articul[$k] = " t.origion_article='$v' ";
    		}
    	
    		$articul[-1] = 0;
    		$sql = 'SELECT `partsid` FROM `crosses_data` `t` '.
    				'JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state=\'1\' AND ('.implode(' OR ', $articul).')'.($brand != '' ? " AND t.origion_brand='$brand'" : '');
    	} else {
    		$sql = 'SELECT `partsid` FROM `crosses_data` `t` '.
    				"JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state='1' AND t.origion_article='$articul'".($brand != '' ? " AND t.origion_brand='$brand'" : '');
    	}
    	
    	$data = $db->createCommand($sql)->queryAll();
    	$partsid = array(0);
    	foreach ($data as $row) {
    		$partsid[$row['partsid']] = " `partsid`='$row[partsid]' ";
    	}
    	
    	$data_crosses = $db->createCommand('SELECT `origion_article` AS `article`, `origion_brand` AS `brand`, `t_base`.`garanty` AS `garanty` FROM `crosses_data` `t` '.
    									   'JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state = \'1\' AND ( '.implode(' OR ', $partsid).') ')->queryAll();
    	
    	$this->crosses = array();
    	
    	foreach ($data_crosses as $row) {
    		$this->crosses[$row['article']] = array('article' => $row['article'],
    										  		'brand'   => array($row['brand']));
    	}
    	
    	$articul = $temp_articul;
    	
    	if (is_array($articul)) {
    		foreach ($articul as $art) {
    			$this->getCrossesFromUrl($art, $brand);
    		}
    	} else {
    		$this->getCrossesFromUrl($articul, $brand);
    	}
    	
    	/*ob_start();
    	echo '<pre>'; print_r($this->crosses); echo '</pre>';
    	file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', ob_get_clean(), FILE_APPEND);*/
    	
    	return $this->crosses;
    }
    
    private function getCrossesFromUrl($article, $brand)
    {
    	$url = $this->cross_server_name.'/api/?act=articles&auth_key='.$this->cross_pass.'&article='.$article.($brand != '' ? '&brand='.$brand : '').'&brands=on&alias=on';

    	$data = @file_get_contents($url);
    	
    	//file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', $url, FILE_APPEND);
    	
    	if (!empty($data)) {
    		$parts = new SimpleXMLElement($data);
    			
    		foreach ($parts->article as $article) {
    			$a = preg_replace("/[^a-zA-Z0-9]/", "", mb_strtoupper((string) $article->article));
    			$b = mb_strtoupper(trim($article->brand));
    			
    			if (array_key_exists($a, $this->crosses)) {
    				$e = $this->crosses[$a];
    				
    				if (array_key_exists('brand', $e)) {
    					if (!is_array($e['brand'])) {
    						$e['brand'] = array($e['brand']);
    					}
    				} else {
    					$e['brand'] = array();
    				}
    				
    				if (!in_array($b, $e['brand'])) $e['brand'][] = $b;
    			} else {
	    			$e = array('id'		 => (string) $article->id,
	    					   'article' => $a,
	    					   'brand'   => array($b),
	    					   'aliases' => array());
    			}
    			
    			foreach ($article->alias as $alias) {
    				$e['aliases'][] = mb_strtoupper(trim((string) $alias));
    			}
    				
    			$this->crosses[$a] = $e;
    		}
    	}
    }
    
    public function getCrosses($articul, $brand = '')
    {
        $temp_articul = $articul;
        
        $brand = trim(mb_strtolower($brand));
        
        $db = Yii::app()->db;
        if (is_array($articul)) {
            foreach ($articul as $k => $v) {
                $articul[$k] = " t.origion_article='$v' ";
            }
            
            $articul[-1] = 0;
            $sql = 'SELECT `partsid` FROM `crosses_data` `t` '.
            	   'JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state=\'1\' AND ('.implode(' OR ', $articul).')'.($brand != '' ? " AND t.origion_brand='$brand'" : '');
        } else {
            $sql = 'SELECT `partsid` FROM `crosses_data` `t` '.
            	   "JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state='1' AND t.origion_article='$articul'".($brand != '' ? " AND t.origion_brand='$brand'" : '');
        }
        
        $data = $db->createCommand($sql)->queryAll();
        $partsid = array(0);
        foreach ($data as $row) {
            $partsid[$row['partsid']] = " `partsid`='$row[partsid]' ";
        }
        
        $data_crosses = $db->createCommand('SELECT `origion_article` AS `article`, `t_base`.`garanty` AS `garanty` FROM `crosses_data` `t` '.
        								   'JOIN `crosses_base` `t_base` ON t.base_id = t_base.id WHERE t_base.active_state = \'1\' AND ( '.implode(' OR ', $partsid).') ')->queryAll();
        
        $crosses = array();
        $garanty = array();
        foreach ($data_crosses as $row) {
            $crosses[$row['article']] = $row['article'];
            if ($row['garanty'] == '1') {
                $garanty[$row['article']] = $row['article'];
            }
        }

        $this->garanty = $garanty;

        $articul = $temp_articul;
        if (is_array($articul)) {
            foreach ($articul as $art) {
                $url = "$this->cross_server_name/getarts.php?num=$art&u=$this->cross_login&p=$this->cross_pass&full=on".($brand != '' ? '&brand='.$brand : '');
                $data = @file_get_contents($url);
                if (!empty($data)) {
                    $data = explode("<br>", $data);
                    foreach ($data as $row_data) {
                        $row = preg_replace("/[^a-zA-Z0-9]/", "", $row_data);
                        if (empty($row))
                            continue;
                        $crosses[$row] = $row;
                    }
                }
            }
        } else {
            $url = "$this->cross_server_name/getarts.php?num=$articul&u=$this->cross_login&p=$this->cross_pass&full=on".($brand != '' ? '&brand='.$brand : '');
            //echo $url;exit;
            /**
             * @todo Здесь апи возвращает ограмную строку, время ответа 0.7 сек, может быть оптимизировать ответ сервера?
             */
            $data = @file_get_contents($url);
            if (!empty($data)) {
                $data = explode("<br>", $data);
                foreach ($data as $row_data) {
                    $row = preg_replace("/[^a-zA-Z0-9]/", "", $row_data);
                    if (empty($row))
                        continue;
                    $crosses[$row] = $row;
                }
            }
        }

        //echo CVarDumper::dump($crosses,10,true);exit;
        
        //file_put_contents(Yii::getPathOfAlias('webroot').'/sql.sql', $url."\n\n", FILE_APPEND);
        
        return $crosses;
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}