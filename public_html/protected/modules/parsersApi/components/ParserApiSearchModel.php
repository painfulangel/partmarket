<?php
class ParserApiSearchModel extends ParserSearchModel {
    public function getData($articul, $params = array()) {
        $this->data = Yii::app()->getModule('parsersApi')->getSupplierDataList($articul, $this->model->supplier_code, $this->search_brand);
        parent::getData($articul, $params);
        return $this->data;
    }

    public function getBrandData($articul, $params = array()) {
    	$this->data = Yii::app()->getModule('parsersApi')->getBrandDataList($articul, $this->model->supplier_code);
    	//parent::getData($articul, $params);
    	return $this->data;
    }
}
