<?php
class DDetailSearchModelClass
{
    public $data = array();
    public $model = null;
    public $search_brand = '';

    public function getData($articul, $params = array())
    {
        $data = $this->data;
        $this->data = array();
        $garanty = Yii::app()->getModule('crosses')->getGaranty($articul);
        //print_r($data);
        foreach ($data as $row) {
//            $row['articul'] = preg_replace("/[^a-zA-Z0-9]/", "", $row['articul']);

        	/*ob_start();
        	echo '<pre>'; print_r($articul); echo '</pre>';
        	file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/2.txt', $row['articul']." - ".ob_get_clean()."\n\n\n", FILE_APPEND);*/
        	
			if (is_array($articul) && !in_array($row['articul'], $articul)) {
	            if (isset($garanty[$row['articul']])) {
	                $row['garanty'] = 1;
	            } else {
	                $row['garanty'] = 0;
	            }
			}
			
            $this->data[] = $row;
        }
    }
}