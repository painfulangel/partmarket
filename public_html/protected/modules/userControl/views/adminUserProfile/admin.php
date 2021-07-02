<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Users')));

$this->pageTitle = Yii::t('userControl', 'Users');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-profile-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1><?php echo Yii::t('userControl', 'Users'); ?></h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-profile-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'uid',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'email',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'fio',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'phone',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'balance',
//            'filter' => '',
            'value' => 'CHtml::link(Yii::app()->getModule(\'currencies\')->getFormatPrice($data->balance),Yii::app()->createUrl(\'/userControl/adminUserBalance/admin\',array(\'id\'=>$data->uid)),array(\'target\'=>\'_blank\'))',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'filter' => '',
            'name' => 'orders_count',
            'value' => '$data->orders_count==0?$data->orders_count:CHtml::link($data->orders_count,Yii::app()->createUrl(\'/shop_cart/adminOrders/admin\',array(\'Orders[user_id]\'=>$data->uid)),array(\'target\'=>\'_blank\'))',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'filter' => '',
            'name' => 'items_count',
            'value' => '$data->items_count==0?$data->items_count:CHtml::link($data->items_count,Yii::app()->createUrl(\'/shop_cart/adminItems/admin\',array(\'Items[user_id]\'=>$data->uid)),array(\'target\'=>\'_blank\'))',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'filter' => '',
            'name' => 'cars_count',
            'value' => '($data->cars_count == 0 ? $data->cars_count:CHtml::link($data->cars_count,Yii::app()->createUrl(\'/userControl/adminUsersCars/admin\',array(\'UsersCars[user_id]\'=>$data->uid)),array(\'target\'=>\'_blank\'))).\'<br/><a href="/userControl/adminUsersCars/create/?user_id=\'.$data->uid.\'" target="_blank">'.Yii::t('userControl', 'Add').'</a>\'',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'filter' => '',
            'name' => 'messages',
            'value' => '$data->getFormatUserMessages()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete} {loginAsUser} ',
            'deleteConfirmation' => Yii::t('userControl', 'Are you sure you want to remove this user, remote users will be removed all his Orders?'),
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'view\',\'id\'=>$data->uid)',
                    'icon' => 'edit-user',
                ),
                'loginAsUser' => array(
                    'url' => 'array(\'loginAsUser\',\'id\'=>$data->uid)',
                    'visible' => 'Yii::app()->user->checkAccess(\'UserNameOrder\')&&UserProfile::getUserActiveId()==Yii::app()->user->id',
                    'icon' => 'user-login',
                    'label' => Yii::t('userControl', 'Login as the user'),
//                    'options' => array('class' => 'admin_order_buttons'),
                ),
//                'logoutAsUser' => array(
//                    'url' => 'array(\'logoutAsUser\',\'id\'=>$data->uid)',
//                    'visible' => 'Yii::app()->user->checkAccess(\'UserNameOrder\')&&UserProfile::getUserActiveId()!=Yii::app()->user->id',
//                    'imageUrl' => '/images/icons/users_out.png',
//                    'label' => 'Выйти от имени пользователя',
//                    'options' => array('class' => 'admin_order_buttons'),
//                ),
                'delete' => array(
                    'url' => 'array(\'delete\',\'id\'=>$data->uid)',
                ),
            ),
        ),
    ),
));
?>