<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('currencies', 'Editing currency')));

$this->pageTitle = Yii::t('currencies', 'Editing currency');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('currencies-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?= Yii::t('currencies', 'Editing currency') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('currencies', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'currencies-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'exchange',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'marker',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'visibility_state',
            'filter' => array('0' => Yii::t('currencies', 'No'), '1' => Yii::t('currencies', 'Yes')),
            'checkedButtonLabel' => Yii::t('currencies', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('currencies', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'basic',
            'filter' => array('0' => Yii::t('currencies', 'No'), '1' => Yii::t('currencies', 'Yes')),
            'checkedButtonLabel' => Yii::t('currencies', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('currencies', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Currencies_page\' => (isset($_GET[\'Currencies_page\']) ? $_GET[\'Currencies_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>
