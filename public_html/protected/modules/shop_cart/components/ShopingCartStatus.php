<?php
class ShopingCartStatus {
	protected $IC_ERROR = 'Заказ ведеться в 1С.';
	protected $STATUS_ERROR = '';
	protected $type = '';
	protected $STATUS_SUCCESS = 'Статус успешно изменен.';
	public $statusList = array(
			'1' => 'Принят к обработке',
			'2' => 'Заказ оформлен',
			'4' => 'Частичный резерв',
			'5' => 'Резерв',
			'6' => 'Готов к выдаче',
			'7' => 'Частично выдан',
			'8' => 'Выполнен',
			'9' => 'Отказ' 
	);
	
	public $statusTranslitList = array(
			'1' => 'Prinyat k robote',
			'2' => 'Oformlen',
			'4' => 'Chast. reserv',
			'5' => 'Reserv',
			'6' => 'Gotov k vudache',
			'7' => 'Chast. vudan',
			'8' => 'Vupolnen',
			'9' => 'Otkaz' 
	);
	
	public $payedList = array(
			'0' => 'Не оплачен',
			'1' => 'Частично оплачен',
			'2' => 'Оплачено' 
	);
	
	public function __construct(){
		$this->STATUS_ERROR = Yii::t('shop_cart', 'Status changed successfully.');
		$this->IC_ERROR = Yii::t('shop_cart', 'Ordering is conducted at 1C.');
		$this->statusList = array(
				'1' => Yii::t('shop_cart', 'Accepted for processing'),
				'2' => Yii::t('shop_cart', 'Check features'),
				'4' => Yii::t('shop_cart', 'Partial reserve'),
				'5' => Yii::t('shop_cart', 'Reserve'),
				'6' => Yii::t('shop_cart', 'Ready to issue'),
				'7' => Yii::t('shop_cart', 'Partially issued'),
				'8' => Yii::t('shop_cart', 'Done'),
				'9' => Yii::t('shop_cart', 'Failure')
		);
		$this->statusTranslitList = array(
				'1' => Yii::t('shop_cart', 'Prinyat k robote'),
				'2' => Yii::t('shop_cart', 'Oformlen'),
				'4' => Yii::t('shop_cart', 'Chast. reserv'),
				'5' => Yii::t('shop_cart', 'Reserv'),
				'6' => Yii::t('shop_cart', 'Gotov k vudache'),
				'7' => Yii::t('shop_cart', 'Chast. vudan'),
				'8' => Yii::t('shop_cart', 'Vupolnen'),
				'9' => Yii::t('shop_cart', 'Otkaz')
		);
		$this->payedList = array(
				'0' => Yii::t('shop_cart', 'Not paid'),
				'1' => Yii::t('shop_cart', 'Partly paid'),
				'2' => Yii::t('shop_cart', 'Paid')
		);
	}
	
	public function getSearchList(){
		return array(
				'0' => Yii::t('shop_cart', 'All'),
				'1' => Yii::t('shop_cart', 'Accepted for processing'),
				'2' => Yii::t('shop_cart', 'Check features'),
				'4' => Yii::t('shop_cart', 'Partial reserve'),
				'5' => Yii::t('shop_cart', 'Reserve'),
				'6' => Yii::t('shop_cart', 'Ready to issue'),
				'7' => Yii::t('shop_cart', 'Partially issued'),
				'8' => Yii::t('shop_cart', 'Done'),
				'9' => Yii::t('shop_cart', 'Failure')
		);
		// return array_merge(array(), $this->statusList);
	}
	
	public function getList(){
		return $this->statusList;
	}
	
	public function getName($id){
		if($id == 0){
			return Yii::t('shop_cart', 'Check by the manager is expected');
		}
		if(isset($this->statusList [$id]))
			return $this->statusList [$id];
		return '';
	}
	
	public function getTranslitName($id){
		if($id == 0){
			return Yii::t('shop_cart', 'Ozhidaetsa proverka');
		}
		if(isset($this->statusTranslitList [$id]))
			return $this->statusTranslitList [$id];
		return '';
	}
	
	public function getPayedSearchList(){
		return $this->payedList;
	}
	
	public function getPayedList(){
		return $this->payedList;
	}
	
	public function getPayedName($id){
		if(isset($this->payedList [$id]))
			return $this->payedList [$id];
		return '';
	}
	
	public function get1CStatus($model){
		if($model->ic_status == 0){
			return false;
		} else {
			return true;
		}
	}
	
	public function getSearchBlock($data){
		ob_start();
		$form = Yii::app()->controller->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'orders-form',
				'enableAjaxValidation' => false 
			// 'htmlOptions' => array('enctype' => 'multipart/form-data', 'target' => '_footer_iframe',),
		));
		$temp = ob_get_clean();
		$formToStore = $form->dropDownList($data, 'status', $this->getSearchList(), array(
				'class' => 'span2' 
		));
		ob_start();
		Yii::app()->controller->endWidget();
		$temp .= ob_get_clean();
		return $formToStore;
	}
	
	public function getUpdateBlock($data){
		$csrfName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$text_check = Yii::t('shop_cart', 'Confirm the status change');
		$text_error = Yii::t('shop_cart', 'An error occurred.');
		$text_name = Yii::t('shop_cart', 'Change of the status');
		Yii::app()->clientScript->registerScript('#ShopCartUpdateFunction', <<<EOP
function ShopCartUpdateStatus(url,id,type){
        if(window.confirm("$text_check")){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data:
            {
                model_id:id,
                status_id:$('#select_'+id).val(),
                $csrfName : '$csrfToken',
            },
            dataType: "json",
            timeout: 5000,
            success: function(data){
                ShowWindow('$text_name',data.msg);
                $.fn.yiiGridView.update(type+"-grid");
            },
            error: function(request, status, error){
//                alert(request.responseText);
                alert('$text_error');
            }
        });
}}			
EOP
, CClientScript::POS_END);
		
		ob_start();
		$form = Yii::app()->controller->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'orders-form',
				'enableAjaxValidation' => false 
			// 'htmlOptions' => array('enctype' => 'multipart/form-data', 'target' => '_footer_iframe',),
		));
		$temp = ob_get_clean();
		
		$formToStore = $form->dropDownList($data, 'status', $this->getList(), array(
				//'disabled'.(in_array($data->status, array(8, 9)) || $data->payed_status == 2 || $this->get1CStatus($data)||(! Yii::app()->user->checkAccess('managerNotDiscount')&& ! Yii::app()->user->checkAccess('manager')&& ! Yii::app()->user->checkAccess('mainManager')&& ! Yii::app()->user->checkAccess('admin')&& $data->status != 1)? '' : '1') => 'on',
				'disabled'.(in_array($data->status, array(8, 9)) || $this->get1CStatus($data)||(! Yii::app()->user->checkAccess('managerNotDiscount')&& ! Yii::app()->user->checkAccess('manager')&& ! Yii::app()->user->checkAccess('mainManager')&& ! Yii::app()->user->checkAccess('admin')&& $data->status != 1)? '' : '1') => 'on',
				'class' => 'span2',
				'style' => 'min-width: 170px;',
				'id' => 'select_'.$data->id,
				'onchange' => 'ShopCartUpdateStatus("'.Yii::app()->createUrl('/shop_cart/admin'.$this->type.'/updateStatus').'",'.$data->id.',"'.$this->type.'")' 
		));
		ob_start();
		Yii::app()->controller->endWidget();
		$temp .= ob_get_clean();
		return $formToStore;
	}
	
	public function getDoneBlock($data){
		if (in_array($data->status, array(8, 9))/* || $data->payed_status == 2*/ || $this->get1CStatus($data)){
			return '';
		}
		
		$csrfName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$text_check1 = Yii::t('shop_cart', 'To really complete the order?');
		$text_check2 = Yii::t('shop_cart', 'Make a Deposit and withdraw funds in the amount of the client\'s order, click - Ok, to produce only debiting the client, click - Сancel?');
		$text_error = Yii::t('shop_cart', 'An error occurred.');
		$text_name = Yii::t('shop_cart', 'Order execution');
		Yii::app()->clientScript->registerScript('#ShopCartDoneFunction', <<<EOP
    function ShopCartDoneStatus(url,id,type){
                var input_money=0;
        if(window.confirm("$text_check1")){
        if(window.confirm("$text_check2")){
            input_money=1;
        }
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data:
            {
                model_id:id,
                flag:input_money,
                status_id:8,
                $csrfName : '$csrfToken',
            },
            dataType: "json",
            timeout: 5000,
            success: function(data){
                ShowWindow('$text_name',data.msg);
                $.fn.yiiGridView.update(type+"-grid");
            },
            error: function(){
                alert('$text_error');
            }
        });
        }
            return false;}

EOP
, CClientScript::POS_END);
		
		return CHtml::link(Yii::t('shop_cart', 'Run'), '', array(
				'class' => 'btn ',
				'onclick' => 'ShopCartDoneStatus("'.Yii::app()->createUrl('/shop_cart/admin'.$this->type.'/updateStatus').'",'.$data->id.',"'.$this->type.'")' 
		));
	}
	
	public function getRefundBlock($data){
		$sum = - $data->left_pay;
		if($data->left_pay >= 0)
			return '';
		
		$text = '<p>'.Yii::t('shop_cart', 'The refund amount:').' '.$sum.'</p>';
		
		$csrfName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		
		$text_check = Yii::t('shop_cart', 'Confirm the refund in the amount of');
		$text_error = Yii::t('shop_cart', 'An error occurred.');
		$text_name = Yii::t('shop_cart', 'Refunds');
		
		Yii::app()->clientScript->registerScript('#ShopCartRefundMoney', <<<EOP
    function ShopCartRefundMoney(url,id,type,sum){
        if(window.confirm("$text_check " +sum)){
        
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data:
            {
                model_id:id,
                $csrfName : '$csrfToken',
            },
            dataType: "json",
            timeout: 5000,
            success: function(data){
                ShowWindow('$text_name',data.msg);
                $.fn.yiiGridView.update(type+"-grid");
            },
            error: function(){
                alert('$text_error');
            }
        });
        }
            return false;}

EOP
, CClientScript::POS_END);
		
		return $text.CHtml::link(Yii::t('shop_cart', 'To get the money back'), '', array(
				'class' => 'btn btn-primary',
				'onclick' => 'ShopCartRefundMoney("'.Yii::app()->createUrl('/shop_cart/admin'.$this->type.'/refundMoney').'",'.$data->id.',"'.$this->type.'","'.$sum.'")' 
		));
	}
}