<?php
class Redactor extends CInputWidget
{
	public $form;
	public $model;
	public $attribute;
	
	public function run(){
		$type = Yii::app()->config->get('Site.Redactor');
		
		$this->render('index', array('type' => $type, 'form' => $this->form, 'model' => $this->model, 'attribute' => $this->attribute));
	}
}
?>