<?php
	$title = Yii::t('universal', 'Universal catalog chars').(is_object($razdel) ? '"'.$razdel->name.'"' : '');

	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('/universal/admin/admin'), $title));

	$this->pageTitle = $title;
?>
<h1><?php echo $title; ?></h1>
<?php /**/ ?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('universal', 'Create universal catalog chars'), array('create', 'id' => $razdel->primaryKey), array('class' => 'btn')); ?>
    <?php if ($count > 1) echo CHtml::link(Yii::t('universal', 'Copy characteristics from other section'), array('copy', 'id' => $razdel->primaryKey), array('class' => 'btn')); ?>
</div>
<?php
/**/
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'type',
        	'filter' => UniversalChars::getTypes(),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'UniversalChars::getType($data->type)'
    	),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'filter',
    		'filter' => array('0' => Yii::t('universal', 'No'), '1' => Yii::t('universal', 'Yes')),
    		'checkedButtonLabel' => Yii::t('universal', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('universal', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'filter_main',
    		'filter' => array('0' => Yii::t('universal', 'No'), '1' => Yii::t('universal', 'Yes')),
    		'checkedButtonLabel' => Yii::t('universal', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('universal', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        ),
    ),
))
?>