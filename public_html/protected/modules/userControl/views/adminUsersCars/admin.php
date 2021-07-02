<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Users') => '/userControl/adminUserProfile/admin', Yii::t('userControl', 'Cars user').' '.$name));

$this->pageTitle = Yii::t('userControl', 'Cars user');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('users-cars-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1><?php echo Yii::t('userControl', 'Cars user').' '.$name; ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('userControl', 'Create'), array('create', 'user_id' => $user_id), array('class' => 'btn')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'users-cars-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'type' => 'raw',
            'value' => '$data->User->getFullName()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'model',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'vin',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'year',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        ),
    ),
));